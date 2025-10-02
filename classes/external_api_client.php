<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace aiprovider_datacurso;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');

/**
 * Minimal HTTP client to interact with Datacurso registration API.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external_api_client {
    /**
     * POST the site registration payload to the configured endpoint.
     * The bearer token is read from plugin settings.
     *
     * @param array $payload
     * @return array Array with 'ok' (bool), 'status' (string), optional 'error'
     */
    public function register_site(array $payload): array {
        global $CFG;

        $endpoint = (string)get_config('aiprovider_datacurso', 'registrationapiurl');
        $bearer = (string)get_config('aiprovider_datacurso', 'registrationapibearer');

        if (empty($endpoint) || empty($bearer)) {
            return [
                'ok' => false,
                'status' => 'not_configured',
                'error' => 'Missing endpoint or bearer token in plugin settings',
            ];
        }

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $bearer,
        ];

        $curl = new \curl();
        $options = [
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_HTTPHEADER' => $headers,
            'CURLOPT_TIMEOUT' => 30,
            'CURLOPT_CONNECTTIMEOUT' => 10,
        ];

        try {
            $response = $curl->post($endpoint, json_encode($payload), $options);
        } catch (\Exception $e) {
            debugging('Registration request failed: ' . $e->getMessage(), DEBUG_DEVELOPER);
            return [
                'ok' => false,
                'status' => 'request_failed',
                'error' => $e->getMessage(),
            ];
        }

        if ($curl->get_errno() !== 0) {
            return [
                'ok' => false,
                'status' => 'curl_error',
                'error' => $curl->error,
            ];
        }

        $httpcode = $curl->get_info()['http_code'] ?? 0;
        if ($httpcode >= 200 && $httpcode < 300) {
            return [
                'ok' => true,
                'status' => 'registered',
            ];
        }

        return [
            'ok' => false,
            'status' => 'http_' . $httpcode,
            'error' => (string)$response,
        ];
    }
}

