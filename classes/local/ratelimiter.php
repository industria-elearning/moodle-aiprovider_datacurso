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
     * Determine if the given user is allowed to use a service (and optional action).
     *
     * This checks, in order:
     * - Empty service id: allow.
     * - Site administrators: always allow.
     * - If user restriction for the service is disabled: allow.
     * - Otherwise, only allow users listed in the configured allowed users list
     *   for the specific service/action pair.
     *
     * @param string $serviceid Service identifier such as 'local_coursegen'.
     * @param int $userid Moodle user id.
     * @param string|null $actionpath Optional HTTP path used to route the request.
     * @return bool True if the user is allowed to access the service, false otherwise.
     */
    public function is_user_allowed(string $serviceid, int $userid, ?string $actionpath = null): bool {
        if (empty($serviceid)) {
            return true;
        }

        if (is_siteadmin($userid)) {
            return true;
        }

        if (!$this->is_user_restriction_enabled($serviceid)) {
            return true;
        }

        // Delegate to the ratelimit settings class for this service in order to
        // keep per-action user lists isolated.
        $alloweduserids = \aiprovider_datacurso\local\ratelimit\ratelimit_settings::get_allowed_users_for_service(
            $serviceid,
            $actionpath
        );

        // When there is no specific list for this service/action, treat it as
        // unrestricted.
        if (empty($alloweduserids)) {
            return true;
        }

        return in_array($userid, $alloweduserids, true);
    }

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
        $record = $DB->get_record('aiprovider_datacurso_rlimit', [
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
     * Immediately increment the local token usage counter by 1 for the given service/user.
     *
     * This provides a reliable, remote-independent guard: even if the subsequent remote
     * sync fails, the DB already reflects that one more token was consumed. The sync can
     * later overwrite this value with the precise remote count once it succeeds.
     *
     * Window logic mirrors sync_after_success: if the current window has elapsed the
     * window is advanced before incrementing so that the new usage starts a fresh window.
     *
     * @param string $serviceid Service identifier such as 'local_coursegen'.
     * @param int    $userid    Moodle user id.
     * @return void
     */
    public function increment_local_usage(string $serviceid, int $userid): void {
        if (!$this->is_rate_limit_enabled($serviceid)) {
            return;
        }

        $limit = $this->get_service_limit($serviceid);
        if ($limit <= 0) {
            return;
        }

        $windowseconds = $this->get_window_length_in_seconds($serviceid);
        $now = time();

        // Load or create the usage record (windowstart = $now on first creation).
        $record = $this->load_usage_record($userid, $serviceid, $now);

        $activewindowstart = (int)($record->windowstart ?? 0);
        if ($activewindowstart <= 0) {
            $activewindowstart = $now;
        }

        $windowend = $activewindowstart + $windowseconds;

        // If the stored window has expired, advance it before counting.
        if ($now >= $windowend) {
            $elapsed       = $now - $activewindowstart;
            $windowsadvance = intdiv($elapsed, $windowseconds);
            if ($windowsadvance > 0) {
                $activewindowstart += $windowsadvance * $windowseconds;
            }
            // Reset the counter because we are now in a new window.
            $record->tokensused = 0;
        }

        // Increment by 1 and persist immediately.
        $newcount = (int)($record->tokensused ?? 0) + 1;
        $this->update_usage_record($record, $newcount, $activewindowstart, $now);
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
            '/chat/' => 'local_dttutor',
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
     * Determine whether the user restriction is enabled for the service.
     *
     * @param string $serviceid Service identifier such as 'local_coursegen'.
     * @return bool True when the user restriction is enabled, false otherwise.
     */
    private function is_user_restriction_enabled(string $serviceid): bool {
        $value = get_config('aiprovider_datacurso', "ratelimit_{$serviceid}_allowedusers_enable");
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

        $record = $DB->get_record('aiprovider_datacurso_rlimit', [
            'userid' => $userid,
            'serviceid' => $serviceid,
        ]);

        if ($record) {
            return $record;
        }

        $record = $this->create_usage_record($userid, $serviceid, $windowstart);
        $record->id = $DB->insert_record('aiprovider_datacurso_rlimit', $record);

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

        $DB->update_record('aiprovider_datacurso_rlimit', $record);
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
        $record = $DB->get_record('aiprovider_datacurso_rlimit', [
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
            $consumptions = $this->extract_consumptions_from_user($user);
            if (empty($consumptions)) {
                continue;
            }

            if (!$this->is_target_user($user, $userid)) {
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
     * @return float
     */
    private function extract_token_amount(array $consumption): float {
        $raw = $consumption['cantidad_tokens'] ?? 0;
        return (float)$raw;
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
        // When no specific service is requested, accept all entries.
        if (empty($serviceid)) {
            return true;
        }
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

    /**
     * Check if the user has remaining quota in the aiprovider_datacurso_userlimit table.
     * Missing record means unlimited (allowed). A configured limit <= 0 blocks access.
     *
     * @param int $userid
     * @return bool
     */
    public function precheck_user_quota(int $userid): bool {
        global $DB;

        $record = $DB->get_record('aiprovider_datacurso_userlimit', ['userid' => $userid]);
        if (!$record) {
            return true;
        }

        $limit = (int)($record->tokenlimit ?? 0);
        if ($limit <= 0) {
            return false;
        }

        $used = (int)($record->tokensused ?? 0);
        return $used < $limit;
    }

    /**
     * Get a snapshot of the user quota values.
     *
     * @param int $userid
     * @return array{limit:int,used:int,remaining:int}|null Null when no record exists.
     */
    public function get_user_quota_snapshot(int $userid): ?array {
        global $DB;
        $record = $DB->get_record('aiprovider_datacurso_userlimit', ['userid' => $userid]);
        if (!$record) {
            return null;
        }
        $limit = (int)($record->tokenlimit ?? 0);
        $used = (int)($record->tokensused ?? 0);
        $remaining = $limit > 0 ? max(0, $limit - $used) : PHP_INT_MAX;
        return [
            'limit' => $limit,
            'used' => $used,
            'remaining' => $remaining,
        ];
    }

    /**
     * After a successful request, refresh the user quota usage by summing remote consumptions
     * from the configured countfrom timestamp up to now (all services/actions).
     *
     * @param int $userid
     * @param string|null $actionpath Optional action path to help remote filtering (not required).
     * @return void
     */
    public function sync_user_quota_after_success(int $userid, ?string $actionpath = null): void {
        global $DB;

        $record = $DB->get_record('aiprovider_datacurso_userlimit', ['userid' => $userid]);
        if (!$record) {
            return; // No quota set; nothing to sync.
        }

        $limit = (int)($record->tokenlimit ?? 0);
        if ($limit <= 0) {
            return; // Unlimited.
        }

        $from = (int)($record->countfrom ?? 0);
        $now = time();

        $tokensused = $this->get_user_tokens_since($userid, $from > 0 ? $from : 0, $now, $actionpath);

        $record->tokensused = $tokensused;
        $record->lastsync = $now;
        $record->timemodified = $now;
        $DB->update_record('aiprovider_datacurso_userlimit', $record);
    }

    /**
     * Sum total tokens consumed by the user between two timestamps across all services.
     *
     * @param int $userid
     * @param int $from
     * @param int $to
     * @param string|null $actionpath
     * @return int
     */
    private function get_user_tokens_since(int $userid, int $from, int $to, ?string $actionpath = null): int {
        $client = new \aiprovider_datacurso\httpclient\datacurso_api();

        $page = 1;
        $limit = 100;
        $tokens = 0;

        while (true) {
            $response = $this->request_consumption_page(
                $client,
                $userid,
                '', // No service filter: all services.
                $actionpath,
                $from,
                $to,
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

            // API is filtered by userid; take the first entry.
            $user = $users[0];
            $consumptions = $this->extract_consumptions_from_user($user);
            if (!empty($consumptions)) {
                // Pass empty service to include all.
                $summary = $this->sum_tokens_from_consumptions(
                    $consumptions,
                    '',
                    null,
                    $from,
                    $to
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
     * Immediately increment the user quota usage counter by 1.
     *
     * This provides a reliable, remote-independent way to track consumption in the
     * aiprovider_datacurso_userlimit table. The remote sync (sync_user_quota_after_success)
     * can still be called periodically via a scheduled task, but this local increment
     * ensures precheck_user_quota() always sees an up-to-date count without depending
     * on the remote API returning consumption data in the expected format.
     *
     * @param int $userid Moodle user id.
     * @return void
     */
    public function increment_user_quota_usage(int $userid): void {
        global $DB;

        $record = $DB->get_record('aiprovider_datacurso_userlimit', ['userid' => $userid]);
        if (!$record) {
            return; // No quota assigned to this user; nothing to increment.
        }

        $limit = (int)($record->tokenlimit ?? 0);
        if ($limit <= 0) {
            return; // Limit 0 or negative means already blocked; skip increment.
        }

        $now = time();
        $record->tokensused   = (int)($record->tokensused ?? 0) + 1;
        $record->timemodified = $now;
        $DB->update_record('aiprovider_datacurso_userlimit', $record);
    }
}
