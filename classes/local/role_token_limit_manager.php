<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace aiprovider_datacurso\local;

use aiprovider_datacurso\httpclient\datacurso_api;
use core\exception\moodle_exception;

/**
 * Manager for CRUD operations over aiprovider_datacurso_rolelimit.
 *
 * @package    aiprovider_datacurso
 * @copyright  2026 Datacurso
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class role_token_limit_manager {
    /** Highest priority level key. */
    public const PRIORITY_HIGH = 'high';

    /** Medium priority level key. */
    public const PRIORITY_MEDIUM = 'medium';

    /** Lowest priority level key. */
    public const PRIORITY_LOW = 'low';

    /**
     * Get current license pool information for role-limit assignments.
     *
     * @param int|null $excludingid Optional role-limit record id to exclude from assigned total.
     * @return array{status:string,licensebalance:int,assignedtotal:int,availabletoassign:int}
     */
    public static function get_license_pool(?int $excludingid = null): array {
        global $DB;

        $rolesql = 'SELECT COALESCE(SUM(tokenlimit), 0) FROM {aiprovider_datacurso_rolelimit}';
        $roleparams = [];
        if (!empty($excludingid)) {
            $rolesql .= ' WHERE id <> :excludingid';
            $roleparams['excludingid'] = $excludingid;
        }
        $roleassigned = (int)$DB->get_field_sql($rolesql, $roleparams);

        $userassigned = 0;
        if ($DB->get_manager()->table_exists('aiprovider_datacurso_userlimit')) {
            $userassigned = (int)$DB->get_field_sql('SELECT COALESCE(SUM(tokenlimit), 0) FROM {aiprovider_datacurso_userlimit}');
        }

        $assignedtotal = $roleassigned + $userassigned;

        try {
            $client = new datacurso_api();
            $response = $client->get('/tokens/saldo');
            if (empty($response) || ($response['status'] ?? 'error') !== 'success') {
                return [
                    'status' => 'error',
                    'licensebalance' => 0,
                    'assignedtotal' => $assignedtotal,
                    'availabletoassign' => 0,
                ];
            }

            $licensebalance = (int)($response['saldo_actual'] ?? 0);
            $availabletoassign = max(0, $licensebalance - $assignedtotal);

            return [
                'status' => 'success',
                'licensebalance' => $licensebalance,
                'assignedtotal' => $assignedtotal,
                'availabletoassign' => $availabletoassign,
            ];
        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'licensebalance' => 0,
                'assignedtotal' => $assignedtotal,
                'availabletoassign' => 0,
            ];
        }
    }

    /**
     * Count records matching optional search.
     *
     * @param string $search
     * @return int
     */
    public static function count(string $search = ''): int {
        global $DB;
        [$where, $params] = self::build_search_where($search);
        $sql = "SELECT COUNT(1)
                  FROM {aiprovider_datacurso_rolelimit} rtl
                  JOIN {role} r ON r.id = rtl.roleid
                  $where";
        return (int)$DB->get_field_sql($sql, $params);
    }

    /**
     * Get paginated records with role data.
     *
     * @param string $search
     * @param string $sort allowed: rolename|roleshortname|priority|tokenlimit|tokensused
     * @param string $dir ASC|DESC
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public static function get_records(string $search, string $sort, string $dir, int $offset, int $limit): array {
        global $DB;
        [$where, $params] = self::build_search_where($search);

        $orderby = self::map_sort($sort, $dir);
        $sql = "SELECT rtl.id, rtl.roleid, rtl.priority, rtl.tokenlimit, rtl.tokensused,
                       r.name AS rolename, r.shortname AS roleshortname
                  FROM {aiprovider_datacurso_rolelimit} rtl
                  JOIN {role} r ON r.id = rtl.roleid
                  $where
              ORDER BY $orderby";
        return $DB->get_records_sql($sql, $params, $offset, $limit);
    }

    /**
     * Get a single record by id.
     *
     * @param int $id
     * @return \stdClass|null
     */
    public static function get_by_id(int $id): ?\stdClass {
        global $DB;
        return $DB->get_record('aiprovider_datacurso_rolelimit', ['id' => $id]) ?: null;
    }

    /**
     * Delete a record by id.
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool {
        global $DB;

        if (!$DB->record_exists('aiprovider_datacurso_rolelimit', ['id' => $id])) {
            return false;
        }

        $transaction = $DB->start_delegated_transaction();
        $DB->delete_records('aiprovider_datacurso_rolelimit_userusage', ['rolelimitid' => $id]);
        $DB->delete_records('aiprovider_datacurso_rolelimit', ['id' => $id]);
        $transaction->allow_commit();
        return true;
    }

    /**
     * Reset usage counters for a role limit record.
     *
     * @param int $id Record ID
     * @return bool
     */
    public static function reset_usage(int $id): bool {
        global $DB, $USER;

        $record = $DB->get_record('aiprovider_datacurso_rolelimit', ['id' => $id]);
        if (!$record) {
            return false;
        }

        $now = time();
        $transaction = $DB->start_delegated_transaction();
        $record->tokensused = 0;
        $record->countfrom = $now;
        $record->lastsync = $now;
        $record->usermodified = $USER->id;
        $record->timemodified = $now;
        $DB->update_record('aiprovider_datacurso_rolelimit', $record);

        $DB->set_field('aiprovider_datacurso_rolelimit_userusage', 'tokensused', 0, ['rolelimitid' => $id]);
        $DB->set_field('aiprovider_datacurso_rolelimit_userusage', 'lastsync', $now, ['rolelimitid' => $id]);
        $DB->set_field('aiprovider_datacurso_rolelimit_userusage', 'timemodified', $now, ['rolelimitid' => $id]);

        $transaction->allow_commit();
        return true;
    }

    /**
     * Process due recurring quota resets.
     *
     * @param int|null $now Optional timestamp used for testing.
     * @return int Number of records successfully reset.
     */
    public static function process_recurring_resets(?int $now = null): int {
        global $DB;

        $now = $now ?? time();
        $records = $DB->get_records_select(
            'aiprovider_datacurso_rolelimit',
            'nextresetat > 0 AND nextresetat <= :now AND recurringintervalenabled = 1 AND recurringintervalvalue > 0',
            ['now' => $now],
            'nextresetat ASC, id ASC'
        );

        $resetcount = 0;
        foreach ($records as $record) {
            [$enabled, $intervalunit, $intervalvalue] = self::extract_interval_config($record);
            if (!$enabled) {
                continue;
            }

            $canreset = false;
            $pool = self::get_license_pool((int)$record->id);
            if (($pool['status'] ?? 'error') === 'success') {
                $canreset = (int)$record->tokenlimit <= (int)$pool['availabletoassign'];
            }

            $transaction = $DB->start_delegated_transaction();
            if ($canreset) {
                $record->tokensused = 0;
                $record->countfrom = $now;
                $record->lastsync = $now;
                $DB->set_field('aiprovider_datacurso_rolelimit_userusage', 'tokensused', 0, ['rolelimitid' => $record->id]);
                $DB->set_field('aiprovider_datacurso_rolelimit_userusage', 'lastsync', $now, ['rolelimitid' => $record->id]);
                $DB->set_field('aiprovider_datacurso_rolelimit_userusage', 'timemodified', $now, ['rolelimitid' => $record->id]);
                $resetcount++;
            }

            $record->nextresetat = self::calculate_next_reset_at(
                (int)$record->nextresetat,
                $enabled,
                $intervalunit,
                $intervalvalue,
                $now
            );
            $record->timemodified = $now;
            $DB->update_record('aiprovider_datacurso_rolelimit', $record);
            $transaction->allow_commit();
        }

        return $resetcount;
    }

    /**
     * Create or update a role quota record.
     *
     * @param int $roleid
     * @param int $tokenlimit
     * @param string $priority
     * @param int|null $id Existing id to update
     * @param int $recurringintervalenabled
     * @param string $recurringintervalunit
     * @param int $recurringintervalvalue
     * @return int
     */
    public static function save(
        int $roleid,
        int $tokenlimit,
        string $priority,
        ?int $id = null,
        int $recurringintervalenabled = 0,
        string $recurringintervalunit = 'day',
        int $recurringintervalvalue = 0
    ): int {
        global $DB, $USER;

        $now = time();
        $recurringintervalenabled = !empty($recurringintervalenabled) ? 1 : 0;
        $recurringintervalunit = self::normalize_interval_unit($recurringintervalunit);
        $recurringintervalvalue = max(0, $recurringintervalvalue);
        $priority = self::normalize_priority($priority);

        if (!$recurringintervalenabled) {
            $recurringintervalvalue = 0;
        }

        $nextresetat = self::calculate_next_reset_at(
            0,
            (bool)$recurringintervalenabled,
            $recurringintervalunit,
            $recurringintervalvalue,
            $now
        );

        $pool = self::get_license_pool($id ?: null);
        if (($pool['status'] ?? 'error') !== 'success') {
            throw new moodle_exception('errorgetbalancecredits', 'aiprovider_datacurso');
        }

        $availabletoassign = (int)($pool['availabletoassign'] ?? 0);
        if ($tokenlimit > $availabletoassign) {
            $params = (object)[
                'requested' => $tokenlimit,
                'available' => $availabletoassign,
            ];
            throw new moodle_exception('error_usertokenlimit_available_exceeded', 'aiprovider_datacurso', '', $params);
        }

        if ($id) {
            $record = $DB->get_record('aiprovider_datacurso_rolelimit', ['id' => $id], '*', MUST_EXIST);
            $record->tokenlimit = $tokenlimit;
            $record->priority = $priority;
            $record->recurringintervalenabled = $recurringintervalenabled;
            $record->recurringintervalunit = $recurringintervalunit;
            $record->recurringintervalvalue = $recurringintervalvalue;
            $record->nextresetat = $nextresetat;
            $record->usermodified = $USER->id;
            $record->timemodified = $now;
            $DB->update_record('aiprovider_datacurso_rolelimit', $record);
            return $record->id;
        }

        if ($DB->record_exists('aiprovider_datacurso_rolelimit', ['roleid' => $roleid])) {
            throw new moodle_exception('error_roletokenlimit_exists', 'aiprovider_datacurso');
        }

        $record = (object)[
            'roleid' => $roleid,
            'priority' => $priority,
            'tokenlimit' => $tokenlimit,
            'tokensused' => 0,
            'countfrom' => $now,
            'lastsync' => 0,
            'recurringintervalenabled' => $recurringintervalenabled,
            'recurringintervalunit' => $recurringintervalunit,
            'recurringintervalvalue' => $recurringintervalvalue,
            'nextresetat' => $nextresetat,
            'usermodified' => $USER->id,
            'timecreated' => $now,
            'timemodified' => $now,
        ];
        return (int)$DB->insert_record('aiprovider_datacurso_rolelimit', $record);
    }

    /**
     * Build search SQL for role name or shortname.
     *
     * @param string $search
     * @return array
     */
    private static function build_search_where(string $search): array {
        global $DB;
        $where = '';
        $params = [];
        if ($search !== '') {
            $where = 'WHERE (' .
                $DB->sql_like('r.name', ':s1', false) . ' OR ' .
                $DB->sql_like('r.shortname', ':s2', false) .
            ')';
            $like = "%$search%";
            $params['s1'] = $like;
            $params['s2'] = $like;
        }
        return [$where, $params];
    }

    /**
     * Map UI sort to SQL order by.
     *
     * @param string $sort
     * @param string $dir
     * @return string
     */
    private static function map_sort(string $sort, string $dir): string {
        $dir = (strtoupper($dir) === 'DESC') ? 'DESC' : 'ASC';
        return match ($sort) {
            'rolename' => "r.name $dir, r.shortname $dir",
            'roleshortname' => "r.shortname $dir",
            'priority' => "CASE
                WHEN rtl.priority = 'high' THEN 1
                WHEN rtl.priority = 'medium' THEN 2
                WHEN rtl.priority = 'low' THEN 3
                ELSE 2
            END $dir",
            'tokenlimit' => "rtl.tokenlimit $dir",
            'tokensused' => "rtl.tokensused $dir",
            'tokensavailable' => "(rtl.tokenlimit - rtl.tokensused) $dir",
            default => "r.shortname $dir",
        };
    }

    /**
     * Normalize priority into the persisted canonical level.
     *
     * @param string $priority
     * @return string
     */
    public static function normalize_priority(string $priority): string {
        $priority = trim(\core_text::strtolower($priority));
        return match ($priority) {
            self::PRIORITY_HIGH => self::PRIORITY_HIGH,
            self::PRIORITY_MEDIUM => self::PRIORITY_MEDIUM,
            self::PRIORITY_LOW => self::PRIORITY_LOW,
            default => self::PRIORITY_MEDIUM,
        };
    }

    /**
     * Convert stored priority value into stable level key.
     *
     * @param string $priority
     * @return string
     */
    public static function priority_to_level(string $priority): string {
        return self::normalize_priority($priority);
    }

    /**
     * Normalize supported interval units.
     *
     * @param string $unit
     * @return string
     */
    private static function normalize_interval_unit(string $unit): string {
        $unit = trim(\core_text::strtolower($unit));
        $supported = ['hour', 'day', 'week', 'month', 'year'];
        return in_array($unit, $supported, true) ? $unit : 'day';
    }

    /**
     * Calculate the next reset timestamp for recurring assignment.
     *
     * @param int $currentnextreset
     * @param bool $enabled
     * @param string $intervalunit
     * @param int $intervalvalue
     * @param int $now
     * @return int
     */
    private static function calculate_next_reset_at(
        int $currentnextreset,
        bool $enabled,
        string $intervalunit,
        int $intervalvalue,
        int $now
    ): int {
        if (!$enabled || $intervalvalue <= 0) {
            return 0;
        }

        $nextresetat = $currentnextreset > 0
            ? $currentnextreset
            : self::add_interval_to_timestamp($now, $intervalunit, $intervalvalue);
        if ($nextresetat <= 0) {
            return 0;
        }

        while ($nextresetat <= $now) {
            $nextresetat = self::add_interval_to_timestamp($nextresetat, $intervalunit, $intervalvalue);
            if ($nextresetat <= 0) {
                return 0;
            }
        }

        return $nextresetat;
    }

    /**
     * Add interval to timestamp using DateTime arithmetic.
     *
     * @param int $timestamp
     * @param string $unit
     * @param int $value
     * @return int
     */
    private static function add_interval_to_timestamp(int $timestamp, string $unit, int $value): int {
        if ($timestamp <= 0 || $value <= 0) {
            return 0;
        }

        $dt = new \DateTimeImmutable('@' . $timestamp);
        $dt = $dt->setTimezone(new \DateTimeZone('UTC'));
        $modified = $dt->modify('+' . $value . ' ' . $unit);
        if ($modified === false) {
            return 0;
        }

        return $modified->getTimestamp();
    }

    /**
     * Get effective recurrence configuration from record.
     *
     * @param \stdClass $record
     * @return array{0:bool,1:string,2:int}
     */
    private static function extract_interval_config(\stdClass $record): array {
        $enabled = !empty($record->recurringintervalenabled);
        $unit = self::normalize_interval_unit((string)($record->recurringintervalunit ?? 'day'));
        $value = (int)($record->recurringintervalvalue ?? 0);
        return [($enabled && $value > 0), $unit, $value];
    }
}
