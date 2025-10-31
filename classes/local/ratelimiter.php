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

/**
 * Per-user rate limiter for Datacurso services.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Wilber Narvaez <https://datacurso.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ratelimiter {
    /**
     * Cached pre-check using only DB data. No remote calls. No writes.
     *
     * @param string|null $serviceid Service identifier such as 'local_coursegen'.
     * @param int $userid Moodle user id.
     * @return bool True when the request is allowed, false when the limit is exceeded.
     */
    public function precheck(?string $serviceid, int $userid): bool {
        if (empty($serviceid)) {
            return true;
        }

        if (!$this->is_rate_limit_enabled($serviceid)) {
            return true;
        }

        $limit = $this->get_service_limit($serviceid);
        if ($limit <= 0) {
            return true;
        }

        $windowseconds = $this->get_window_length_in_seconds($serviceid);
        $currenttime = time();

        // Read-only fetch of existing record. Do not create on precheck.
        global $DB;
        $record = $DB->get_record('aiprovider_datacurso_rl', [
            'userid' => $userid,
            'serviceid' => $serviceid,
        ]);

        // If no record exists yet, allow the request; it will be created on first successful sync.
        if (!$record) {
            return true;
        }

        $activewindowstart = (int)($record->windowstart ?? 0);
        if ($activewindowstart <= 0) {
            return true; // Treat as not started; allowed.
        }

        // If current time is beyond the end of stored window, tokens reset for precheck purposes.
        $windowend = $activewindowstart + $windowseconds;
        if ($currenttime >= $windowend) {
            return true;
        }

        $effectivetokens = (int)($record->tokensused ?? 0);
        return $effectivetokens < $limit;
    }

    /**
     * After a successful request, refresh the cached usage by fetching remote consumption and persisting it.
     *
     * @param string $serviceid
     * @param int $userid
     * @param string|null $actionpath
     * @return void
     */
    public function sync_after_success(string $serviceid, int $userid, ?string $actionpath = null): void {
        if (!$this->is_rate_limit_enabled($serviceid)) {
            return;
        }

        $limit = $this->get_service_limit($serviceid);
        if ($limit <= 0) {
            return;
        }

        $windowseconds = $this->get_window_length_in_seconds($serviceid);
        $currenttime = time();

        $record = $this->load_usage_record($userid, $serviceid, $currenttime);

        $activewindowstart = (int)($record->windowstart ?? 0);
        if ($activewindowstart <= 0) {
            $activewindowstart = $currenttime;
        }

        $windowend = $activewindowstart + $windowseconds;
        if ($currenttime >= $windowend) {
            $elapsed = $currenttime - $activewindowstart;
            $windowsadvance = intdiv($elapsed, $windowseconds);
            if ($windowsadvance > 0) {
                $activewindowstart = $activewindowstart + ($windowsadvance * $windowseconds);
                $windowend = $activewindowstart + $windowseconds;
            }
        }

        $tokensused = $this->get_tokens_used_during_window(
            $userid,
            $serviceid,
            $activewindowstart,
            $windowend,
            $actionpath
        );
        $this->update_usage_record($record, $tokensused, $activewindowstart, $currenttime);
    }

    /**
     * Resolve the configured service id from a request path.
     *
     * @param string $path Request path starting with '/'.
     * @return string|null Matching service id or null when unknown.
     */
    public static function resolve_service_for_path(string $path): ?string {
        $trimmedpath = ltrim($path, '/');
        $normalised = '/' . $trimmedpath;
        $map = [
            '/course/' => 'local_coursegen',
            '/resources/' => 'local_coursegen',
            '/context/' => 'local_coursegen',
            '/assign/' => 'local_assign_ai',
            '/forum/' => 'local_forum_ai',
            '/rating/' => 'local_datacurso_ratings',
            '/certificate/' => 'local_socialcert',
            '/story/' => 'report_lifestory',
            '/smartrules/' => 'local_coursedynamicrules',
            '/provider/' => 'aiprovider_datacurso',
        ];

        foreach ($map as $prefix => $service) {
            if (str_starts_with($normalised, $prefix)) {
                return $service;
            }
        }

        return null;
    }

    /**
     * Determine whether the rate limit is enabled for the service.
     *
     * @param string $serviceid Service identifier such as 'local_coursegen'.
     * @return bool True when the rate limit is enabled, false otherwise.
     */
    private function is_rate_limit_enabled(string $serviceid): bool {
        $value = get_config('aiprovider_datacurso', "ratelimit_{$serviceid}_enable");
        return (int)$value === 1;
    }

    /**
     * Fetch the numeric limit configured for the service.
     *
     * @param string $serviceid
     * @return int
     */
    private function get_service_limit(string $serviceid): int {
        $value = get_config('aiprovider_datacurso', "ratelimit_{$serviceid}_limit");
        return (int)$value;
    }

    /**
     * Resolve the length of the window in seconds.
     *
     * @param string $serviceid
     * @return int
     */
    private function get_window_length_in_seconds(string $serviceid): int {
        $json = (string)get_config('aiprovider_datacurso', "ratelimit_{$serviceid}_window");

        $data = json_decode($json, true);
        if (!is_array($data)) {
            $data = [];
        }

        $value = (int)($data['value'] ?? 1);
        $value = $value > 0 ? $value : 1;

        $unit = (string)($data['unit'] ?? 'hours');
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
     * Calculate the start of the active window relative to now.
     *
     * @param int $currenttime
     * @param int $windowseconds
     * @return int
     */
    private function calculate_window_start(int $currenttime, int $windowseconds): int {
        $candidate = $currenttime - $windowseconds;
        return $candidate > 0 ? $candidate : 0;
    }

    /**
     * Load the cached usage record creating it when not present.
     *
     * @param int $userid
     * @param string $serviceid
     * @param int $windowstart
     * @return \stdClass
     */
    private function load_usage_record(int $userid, string $serviceid, int $windowstart): \stdClass {
        global $DB;

        $record = $DB->get_record('aiprovider_datacurso_rl', [
            'userid' => $userid,
            'serviceid' => $serviceid,
        ]);

        if ($record) {
            return $record;
        }

        $record = $this->create_usage_record($userid, $serviceid, $windowstart);
        $record->id = $DB->insert_record('aiprovider_datacurso_rl', $record);

        return $record;
    }

    /**
     * Build the default usage record.
     *
     * @param int $userid
     * @param string $serviceid
     * @param int $windowstart
     * @return \stdClass
     */
    private function create_usage_record(int $userid, string $serviceid, int $windowstart): \stdClass {
        $now = time();

        return (object)[
            'userid' => $userid,
            'serviceid' => $serviceid,
            'windowstart' => $windowstart,
            'tokensused' => 0,
            'lastsync' => $now,
            'timecreated' => $now,
            'timemodified' => $now,
        ];
    }

    /**
     * Persist the refreshed usage information.
     *
     * @param \stdClass $record
     * @param int $tokensused
     * @param int $windowstart
     * @param int $now
     * @return void
     */
    private function update_usage_record(\stdClass $record, int $tokensused, int $windowstart, int $now): void {
        global $DB;

        $record->windowstart = $windowstart;
        $record->tokensused = $tokensused;
        $record->lastsync = $now;
        $record->timemodified = $now;

        $DB->update_record('aiprovider_datacurso_rl', $record);
    }

    /**
     * Get remaining seconds until the current rate limit window resets for a user/service.
     * Returns 0 when no record exists or when the window has already reset.
     *
     * @param string $serviceid
     * @param int $userid
     * @return int
     */
    public function get_time_until_next_window(string $serviceid, int $userid): int {
        if (!$this->is_rate_limit_enabled($serviceid)) {
            return 0;
        }

        $limit = $this->get_service_limit($serviceid);
        if ($limit <= 0) {
            return 0;
        }

        $windowseconds = $this->get_window_length_in_seconds($serviceid);
        $currenttime = time();

        global $DB;
        $record = $DB->get_record('aiprovider_datacurso_rl', [
            'userid' => $userid,
            'serviceid' => $serviceid,
        ]);

        if (!$record) {
            return 0;
        }

        $activewindowstart = (int)($record->windowstart ?? 0);
        if ($activewindowstart <= 0) {
            return 0;
        }

        $windowend = $activewindowstart + $windowseconds;
        $remaining = $windowend - $currenttime;
        return $remaining > 0 ? $remaining : 0;
    }

    /**
     * Compute tokens consumed within the active window.
     *
     * @param int $userid
     * @param string $serviceid
     * @param int $windowstart
     * @param int $windowend
     * @param string|null $actionpath
     * @return int
     */
    private function get_tokens_used_during_window(
        int $userid,
        string $serviceid,
        int $windowstart,
        int $windowend,
        ?string $actionpath
    ): int {
        $servicename = $this->get_service_display_name($serviceid);
        $client = new \aiprovider_datacurso\httpclient\datacurso_api();

        return $this->fetch_tokens_for_service(
            $client,
            $userid,
            $serviceid,
            $servicename,
            $windowstart,
            $windowend,
            $actionpath
        );
    }

    /**
     * Fetch tokens for the service within the requested window.
     *
     * @param \aiprovider_datacurso\httpclient\datacurso_api $client HTTP client used for the request.
     * @param int $userid User identifier to filter by.
     * @param string $serviceid Service identifier to filter consumptions.
     * @param string|null $servicename Optional human-readable service name.
     * @param int $windowstart Start timestamp of the rate limit window.
     * @param int $windowend End timestamp of the rate limit window.
     * @param string|null $actionfilter Action path to restrict consumptions, null for all.
     * @return int Total tokens consumed within the window.
     */
    private function fetch_tokens_for_service(
        \aiprovider_datacurso\httpclient\datacurso_api $client,
        int $userid,
        string $serviceid,
        ?string $servicename,
        int $windowstart,
        int $windowend,
        ?string $actionfilter
    ): int {
        $page = 1;
        $limit = 100;
        $tokens = 0;

        while (true) {
            $response = $this->request_consumption_page(
                $client,
                $userid,
                $serviceid,
                $actionfilter,
                $windowstart,
                $windowend,
                $page,
                $limit
            );
            if (!$this->is_success_response($response)) {
                break;
            }

            $users = $this->extract_users_from_response($response);
            if (empty($users)) {
                break;
            }

            // The API is already filtered by userid; take the first user entry.
            $user = $users[0];
            $consumptions = $this->extract_consumptions_from_user($user);
            if (!empty($consumptions)) {
                $summary = $this->sum_tokens_from_consumptions(
                    $consumptions,
                    $serviceid,
                    $servicename,
                    $windowstart,
                    $windowend
                );
                $tokens += $summary['tokens'];
                if ($summary['stop']) {
                    break;
                }
            }

            if (!$this->response_has_more_pages($response, $page)) {
                break;
            }

            $page++;
        }

        return $tokens;
    }

    /**
     * Invoke the remote API with the selected filters.
     *
     * @param \aiprovider_datacurso\httpclient\datacurso_api $client HTTP client used for the call.
     * @param int $userid User identifier to query.
     * @param string $serviceid Service identifier to query.
     * @param string|null $actionfilter Optional action path filter.
     * @param int $windowstart Window start timestamp.
     * @param int $windowend Window end timestamp.
     * @param int $page Page number to request.
     * @param int $limit Page size to request.
     * @return array|null API response payload or null on failure.
     */
    private function request_consumption_page(
        \aiprovider_datacurso\httpclient\datacurso_api $client,
        int $userid,
        string $serviceid,
        ?string $actionfilter,
        int $windowstart,
        int $windowend,
        int $page,
        int $limit
    ): ?array {
        $params = [
            'page' => $page,
            'limit' => $limit,
            'userid' => $userid,
            // Sort newest first to allow early stop when crossing the window start.
            'shor' => 'created_at',
            'shor_dir' => 'desc',
        ];

        if (!empty($serviceid)) {
            $params['servicio'] = $serviceid;
        }

        // Constrain by date window if available (YYYY-MM-DD).
        if ($windowstart > 0) {
            $params['fecha_desde'] = userdate($windowstart, '%Y-%m-%d');
        }
        if ($windowend > 0) {
            // Use the day of window end; API is expected to handle inclusive bounds.
            $params['fecha_hasta'] = userdate($windowend, '%Y-%m-%d');
        }

        return $client->get('/tokens/historial-consumos', $params);
    }

    /**
     * Check whether the response reports success.
     *
     * @param array|null $response
     * @return bool
     */
    private function is_success_response(?array $response): bool {
        if (!is_array($response)) {
            return false;
        }

        $status = $response['status'] ?? '';
        return $status === 'success';
    }

    /**
     * Extract the user list from the response payload.
     *
     * @param array $response
     * @return array
     */
    private function extract_users_from_response(array $response): array {
        $users = $response['usuarios'] ?? [];
        return is_array($users) ? $users : [];
    }

    /**
     * Sum tokens for the relevant user within the received page.
     *
     * @param array $users
     * @param int $userid
     * @param string $serviceid
     * @param string|null $servicename
     * @param int $windowstart
     * @param int $windowend
     * @return array ['tokens' => int, 'stop' => bool]
     */
    private function sum_tokens_from_users(
        array $users,
        int $userid,
        string $serviceid,
        ?string $servicename,
        int $windowstart,
        int $windowend
    ): array {
        $tokens = 0;
        $shouldstop = false;

        foreach ($users as $user) {
            if (!$this->is_target_user($user, $userid)) {
                continue;
            }

            $consumptions = $this->extract_consumptions_from_user($user);
            if (empty($consumptions)) {
                continue;
            }

            $summary = $this->sum_tokens_from_consumptions($consumptions, $serviceid, $servicename, $windowstart, $windowend);
            $tokens += $summary['tokens'];

            if ($summary['stop']) {
                $shouldstop = true;
                break;
            }
        }

        return [
            'tokens' => $tokens,
            'stop' => $shouldstop,
        ];
    }

    /**
     * Identify whether the payload belongs to the requested user.
     *
     * @param array $user
     * @param int $userid
     * @return bool
     */
    private function is_target_user(array $user, int $userid): bool {
        if (!array_key_exists('userid', $user) && !array_key_exists('id_usuario', $user)) {
            return true;
        }

        $remoteid = $user['userid'] ?? $user['id_usuario'];
        return (int)$remoteid === $userid;
    }

    /**
     * Retrieve the list of consumptions from a user entry.
     *
     * @param array $user
     * @return array
     */
    private function extract_consumptions_from_user(array $user): array {
        $consumptions = $user['consumos'] ?? [];
        return is_array($consumptions) ? $consumptions : [];
    }

    /**
     * Sum tokens for the consumptions within the time window.
     *
     * @param array $consumptions
     * @param string $serviceid
     * @param string|null $servicename
     * @param int $windowstart
     * @param int $windowend
     * @return array ['tokens' => int, 'stop' => bool]
     */
    private function sum_tokens_from_consumptions(
        array $consumptions,
        string $serviceid,
        ?string $servicename,
        int $windowstart,
        int $windowend
    ): array {
        $tokens = 0;
        $shouldstop = false;

        foreach ($consumptions as $consumption) {
            $timestamp = $this->resolve_consumption_timestamp($consumption);
            if ($timestamp === null) {
                continue;
            }

            if ($timestamp < $windowstart) {
                $shouldstop = true;
                continue;
            }

            if ($timestamp >= $windowend) {
                continue;
            }

            if (!$this->consumption_matches_service($serviceid, $servicename, $consumption)) {
                continue;
            }

            $tokens += $this->extract_token_amount($consumption);
        }

        return [
            'tokens' => $tokens,
            'stop' => $shouldstop,
        ];
    }

    /**
     * Obtain the timestamp recorded for the consumption entry.
     *
     * @param array $consumption
     * @return int|null
     */
    private function resolve_consumption_timestamp(array $consumption): ?int {
        $value = $consumption['created_at'] ?? ($consumption['fecha'] ?? null);
        if (!is_string($value) || $value === '') {
            return null;
        }

        $timestamp = strtotime($value);
        return $timestamp === false ? null : $timestamp;
    }

    /**
     * Extract the number of tokens consumed in the entry.
     *
     * @param array $consumption
     * @return int
     */
    private function extract_token_amount(array $consumption): int {
        $raw = $consumption['cantidad_tokens'] ?? 0;
        return (int)$raw;
    }

    /**
     * Determine whether more pages are available for iteration.
     *
     * @param array $response
     * @param int $currentpage
     * @return bool
     */
    private function response_has_more_pages(array $response, int $currentpage): bool {
        $pagination = $response['pagination'] ?? ($response['paginacion'] ?? []);
        if (!is_array($pagination)) {
            return false;
        }

        $totalpages = (int)($pagination['total_pages'] ?? ($pagination['total_paginas'] ?? 0));
        if ($totalpages <= 0) {
            return false;
        }

        return $currentpage < $totalpages;
    }

    /**
     * Get the user-facing name for a service identifier.
     *
     * @param string $serviceid
     * @return string|null
     */
    private function get_service_display_name(string $serviceid): ?string {
        $services = \aiprovider_datacurso\provider::get_services();
        foreach ($services as $service) {
            if (($service['id'] ?? '') === $serviceid) {
                return $service['name'] ?? null;
            }
        }

        return null;
    }

    /**
     * Determine if a remote consumption entry belongs to the given service.
     *
     * @param string $serviceid
     * @param string|null $servicename
     * @param array $consumption
     * @return bool
     */
    private function consumption_matches_service(string $serviceid, ?string $servicename, array $consumption): bool {
        if (isset($consumption['id_servicio']) && is_string($consumption['id_servicio'])) {
            if ($consumption['id_servicio'] === $serviceid) {
                return true;
            }
        }

        // Fallback: compare using the human-readable name if available in payload.
        $apiname = $consumption['servicio'] ?? '';
        if (is_string($servicename) && $servicename !== '' && is_string($apiname) && $apiname !== '') {
            $normalizedremote = $this->normalise_string($apiname);
            $normalizedlocal = $this->normalise_string($servicename);
            if ($normalizedremote === $normalizedlocal) {
                return true;
            }
        }

        // 3) Last resort: map action path to a service.
        $action = $consumption['accion'] ?? ($consumption['action'] ?? '');
        if (!is_string($action) || $action === '') {
            return false;
        }

        $resolvedservice = self::resolve_service_for_path($action);
        return $resolvedservice === $serviceid;
    }

    /**
     * Normalize a string using lowercase trimming.
     *
     * @param string $value
     * @return string
     */
    private function normalise_string(string $value): string {
        $lowercase = \core_text::strtolower($value);
        return trim($lowercase);
    }
}
