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

defined('MOODLE_INTERNAL') || die();

/**
 * Plugin-level rate limiter for Datacurso provider.
 *
 * - Per-user, per-service usage persisted in DB (`aiprovider_datacurso_rl`).
 * - Periodically syncs from external consumption history to compute tokens used within the configured window.
 * - Window and limit come from admin settings per service.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Wilber Narvaez <https://datacurso.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ratelimiter {
    /** @var int Freshness threshold for remote sync in seconds. */
    private int $syncttl = 60; // 1 minute to avoid calling remote on every request.

    /**
     * Check and increment the counter if allowed for a given service and user.
     *
     * @param string $serviceid Service id as configured in settings (e.g. 'local_coursegen').
     * @param int $userid User id.
     * @return bool True if allowed, false if limit exceeded or not enabled.
     */
    public function check(string $serviceid, int $userid): bool {
        global $DB;

        $enabled = (int) get_config('aiprovider_datacurso', "ratelimit_{$serviceid}_enable");
        if (!$enabled) {
            return true;
        }

        $limit = (int) get_config('aiprovider_datacurso', "ratelimit_{$serviceid}_limit");
        if ($limit <= 0) {
            return true;
        }

        $windowcfg = (string) get_config('aiprovider_datacurso', "ratelimit_{$serviceid}_window");
        $windowsecs = $this->parse_window_seconds($windowcfg);
        if ($windowsecs <= 0) {
            $windowsecs = HOURSECS;
        }

        $now = time();
        $record = $DB->get_record('aiprovider_datacurso_rl', [
            'userid' => $userid,
            'serviceid' => $serviceid,
        ]);

        if (!$record) {
            $record = (object) [
                'userid' => $userid,
                'serviceid' => $serviceid,
                'windowstart' => $now,
                'tokensused' => 0,
                'lastsync' => 0,
                'timecreated' => $now,
                'timemodified' => $now,
            ];
            $record->id = $DB->insert_record('aiprovider_datacurso_rl', $record);
        }

        $windowend = $record->windowstart + $windowsecs;
        if ($now >= $windowend) {
            // Reset window when expired.
            $record->windowstart = $now;
            $record->tokensused = 0;
            $record->timemodified = $now;
            $DB->update_record('aiprovider_datacurso_rl', $record);
            $windowend = $record->windowstart + $windowsecs;
        }

        // Sync from remote if stale or never synced for this window.
        $needssync = ($now - (int)$record->lastsync) >= $this->syncttl;
        if ($needssync) {
            $tokens = $this->fetch_tokens_used_in_window($userid, $serviceid, $record->windowstart, $windowend);
            $record->tokensused = $tokens;
            $record->lastsync = $now;
            $record->timemodified = $now;
            $DB->update_record('aiprovider_datacurso_rl', $record);
        }

        // Enforce.
        return ((int)$record->tokensused) < $limit;
    }

    /**
     * Resolve service id based on request path.
     *
     * @param string $path Request path starting with '/'.
     * @return string|null Service id configured in settings.
     */
    public static function resolve_service_for_path(string $path): ?string {
        $path = '/' . ltrim($path, '/');
        $map = [
            '/course/' => 'local_coursegen',
            '/resources/' => 'tutor_ai',
            '/assign/' => 'local_assign_ai',
            '/forum/' => 'local_forum_ai',
            '/rating/' => 'local_datacurso_ratings',
            '/certificate/' => 'local_socialcert',
            '/story/' => 'story_life_student',
            '/context/' => 'smart_rules',
            '/smartrules/' => 'smart_rules',
        ];
        foreach ($map as $prefix => $service) {
            if (str_starts_with($path, $prefix)) {
                return $service;
            }
        }
        return null;
    }

    /**
     * Parse window seconds from JSON stored setting {value:int, unit:string}.
     *
     * @param string $json JSON string.
     * @return int Seconds.
     */
    private function parse_window_seconds(string $json): int {
        $value = 1;
        $unit = 'hours';
        $data = json_decode($json, true);
        if (is_array($data)) {
            $value = $data['value'] ?? 1;
            $unit = $data['unit'] ?? 'hours';
        }
        if ($value <= 0) {
            $value = 1;
        }
        $multiplier = match ($unit) {
            'seconds' => 1,
            'minutes' => MINSECS,
            'hours' => HOURSECS,
            'days' => DAYSECS,
            default => HOURSECS,
        };
        return $value * $multiplier;
    }

    /**
     * Fetch tokens used in the given window for user and service from external API.
     *
     * @param int $userid
     * @param string $serviceid
     * @param int $windowstart
     * @param int $windowend
     * @return int
     */
    private function fetch_tokens_used_in_window(int $userid, string $serviceid, int $windowstart, int $windowend): int {
        $total = 0;

        // Map service id to human-readable service name from provider for comparison with 'id_servicio'.
        $services = \aiprovider_datacurso\provider::get_services();
        $servicename = null;
        foreach ($services as $service) {
            if ($service['id'] === $serviceid) {
                $servicename = $service['name'];
                break;
            }
        }

        $client = new \aiprovider_datacurso\httpclient\datacurso_api();

        $page = 1;
        $limit = 50;
        $shouldstop = false;
        while (!$shouldstop) {
            $params = [
                'page' => $page,
                'limit' => $limit,
                'userid' => $userid,
            ];
            $response = $client->get('/tokens/historial-consumos', $params);
            if (!is_array($response) || ($response['status'] ?? '') !== 'success') {
                break; // On error, keep current tally.
            }

            $users = $response['usuarios'] ?? [];
            $consumptionslist = [];
            foreach ($users as $user) {
                if (!empty($user['consumos'])) {
                    foreach ($user['consumos'] as $consumption) {
                        $consumptionslist[] = $consumption;
                    }
                }
            }

            if (empty($consumptionslist)) {
                break;
            }

            foreach ($consumptionslist as $consumption) {
                $created = $consumption['created_at'] ?? ($consumption['fecha'] ?? null);
                if (empty($created)) {
                    continue;
                }
                // Created_at is like '2025-10-27 19:07:51'.
                $createdtimestamp = strtotime($created);
                if ($createdtimestamp === false) {
                    continue;
                }
                if ($createdtimestamp < $windowstart) {
                    // Older than window; we can stop iterating further pages assuming API returns desc order.
                    $shouldstop = true;
                    break;
                }
                if ($createdtimestamp >= $windowstart && $createdtimestamp < $windowend) {
                    // Filter by service: match by human service name if present, otherwise by action path prefix mapping.
                    $apiservicename = $consumption['id_servicio'] ?? '';
                    $apiaction = $consumption['accion'] ?? '';
                    $match = false;
                    if (!empty($servicename) && $apiservicename === $servicename) {
                        $match = true;
                    } else if (is_string($apiaction) && $apiaction !== '') {
                        $resolved = self::resolve_service_for_path($apiaction);
                        $match = ($resolved === $serviceid);
                    }
                    if ($match) {
                        $total += (int)($consumption['cantidad_tokens'] ?? 0);
                    }
                }
            }

            // Pagination handling.
            $pagination = $response['pagination'] ?? ($response['paginacion'] ?? []);
            $totalpages = (int)($pagination['total_pages'] ?? ($pagination['total_paginas'] ?? 1));
            if ($shouldstop || $page >= $totalpages) {
                break;
            }
            $page++;
        }

        return $total;
    }
}
