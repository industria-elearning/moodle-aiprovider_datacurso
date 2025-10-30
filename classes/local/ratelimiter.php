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
     * Evaluate the limit for a user/service pair and refresh cached usage.
     *
     * @param string $serviceid Service identifier such as 'local_coursegen'.
     * @param int $userid Moodle user id.
     * @param string|null $actionpath Current request path (e.g. '/course/execute').
     * @return bool True when the request is allowed, false when the limit is exceeded.
     */
    public function check(string $serviceid, int $userid, ?string $actionpath = null): bool {
        if (!$this->is_rate_limit_enabled($serviceid)) {
            return true;
        }

        $limit = $this->get_service_limit($serviceid);
        if ($limit <= 0) {
            return true;
        }

        $windowseconds = $this->get_window_length_in_seconds($serviceid);
        $currenttime = time();
        $windowstart = $this->calculate_window_start($currenttime, $windowseconds);
        $windowend = $windowstart + $windowseconds;

        $record = $this->load_usage_record($userid, $serviceid, $windowstart);
        $tokensused = $this->get_tokens_used_during_window($userid, $serviceid, $windowstart, $windowend, $actionpath);
        $this->update_usage_record($record, $tokensused, $windowstart, $currenttime);

        return $tokensused < $limit;
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
     * @param string $serviceid
     * @return bool
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
        $serviceactions = $this->get_actions_for_service($serviceid);

        $normalisedactions = [];
        foreach ($serviceactions as $serviceaction) {
            $normalisedaction = $this->normalise_action_path($serviceaction);
            if ($normalisedaction !== '') {
                $normalisedactions[] = $normalisedaction;
            }
        }

        if (!empty($actionpath)) {
            $currentaction = $this->normalise_action_path($actionpath);
            if ($currentaction !== '') {
                $normalisedactions[] = $currentaction;
            }
        }

        $uniqueactions = array_unique($normalisedactions);
        $actionfilters = array_values($uniqueactions);
        $client = new \aiprovider_datacurso\httpclient\datacurso_api();

        if (empty($actionfilters)) {
            return $this->fetch_tokens_for_action($client, $userid, $serviceid, $servicename, null, $windowstart, $windowend);
        }

        $total = 0;
        foreach ($actionfilters as $actionfilter) {
            $tokens = $this->fetch_tokens_for_action($client, $userid, $serviceid, $servicename, $actionfilter, $windowstart, $windowend);
            $total += $tokens;
        }

        return $total;
    }

    /**
     * Fetch tokens for a specific action filter.
     *
     * @param \aiprovider_datacurso\httpclient\datacurso_api $client
     * @param int $userid
     * @param string $serviceid
     * @param string|null $servicename
     * @param string|null $actionfilter
     * @param int $windowstart
     * @param int $windowend
     * @return int
     */
    private function fetch_tokens_for_action(
        \aiprovider_datacurso\httpclient\datacurso_api $client,
        int $userid,
        string $serviceid,
        ?string $servicename,
        ?string $actionfilter,
        int $windowstart,
        int $windowend
    ): int {
        $page = 1;
        $limit = 100;
        $tokens = 0;

        while (true) {
            $response = $this->request_consumption_page($client, $userid, $servicename, $actionfilter, $page, $limit);
            if (!$this->is_success_response($response)) {
                break;
            }

            $users = $this->extract_users_from_response($response);
            if (empty($users)) {
                break;
            }

            $summary = $this->sum_tokens_from_users($users, $userid, $serviceid, $servicename, $windowstart, $windowend);
            $tokens += $summary['tokens'];

            if ($summary['stop']) {
                break;
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
     * @param \aiprovider_datacurso\httpclient\datacurso_api $client
     * @param int $userid
     * @param string|null $servicename
     * @param string|null $actionfilter
     * @param int $page
     * @param int $limit
     * @return array|null
     */
    private function request_consumption_page(
        \aiprovider_datacurso\httpclient\datacurso_api $client,
        int $userid,
        ?string $servicename,
        ?string $actionfilter,
        int $page,
        int $limit
    ): ?array {
        $params = [
            'page' => $page,
            'limit' => $limit,
            'userid' => $userid,
        ];

        if (!empty($servicename)) {
            $params['servicio'] = $servicename;
        }
        if (!empty($actionfilter)) {
            $params['accion'] = $actionfilter;
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
                break;
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
        $apiservicename = $consumption['id_servicio'] ?? ($consumption['servicio'] ?? '');
        if (is_string($servicename) && $servicename !== '' && is_string($apiservicename)) {
            $normalizedremote = $this->normalise_string($apiservicename);
            $normalizedlocal = $this->normalise_string($servicename);
            if ($normalizedremote === $normalizedlocal) {
                return true;
            }
        }

        $action = $consumption['accion'] ?? ($consumption['action'] ?? '');
        if (!is_string($action) || $action === '') {
            return false;
        }

        $resolvedservice = self::resolve_service_for_path($action);
        return $resolvedservice === $serviceid;
    }

    /**
     * Get the list of known action endpoints associated with a service.
     *
     * @param string $serviceid
     * @return array
     */
    private function get_actions_for_service(string $serviceid): array {
        $map = [
            'local_coursegen' => [
                '/course/execute',
                '/course/start',
                '/resources/create-mod',
                '/resources/create-mod/stream',
                '/context/upload',
                '/context/upload-model-context',
            ],
            'local_assign_ai' => ['/assign/answer'],
            'local_forum_ai' => ['/forum/chat'],
            'local_datacurso_ratings' => ['/rating/general', '/rating/course', '/rating/query'],
            'local_socialcert' => ['/certificate/answer'],
            'report_lifestory' => ['/story/analysis'],
            'local_coursedynamicrules' => ['/smartrules/create-mod'],
            'aiprovider_datacurso' => ['/provider/chat/completions', '/provider/images/generations'],
        ];

        return $map[$serviceid] ?? [];
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
     * Ensure an action path starts with a leading slash.
     *
     * @param string $value
     * @return string
     */
    private function normalise_action_path(string $value): string {
        $trimmed = trim($value);
        if ($trimmed === '') {
            return '';
        }

        $withoutleading = ltrim($trimmed, '/');
        return '/' . $withoutleading;
    }
}
