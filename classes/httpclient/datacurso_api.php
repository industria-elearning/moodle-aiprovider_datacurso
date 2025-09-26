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

namespace aiprovider_datacurso\httpclient;

use moodle_exception;

/**
 * HTTP client for Tokens Manager API.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class datacurso_api {

    /** @var string */
    private $baseurl;

    /** @var string */
    private $licensekey;

    /** @var bool */
    private $workplace;

    /**
     * Constructor.
     *
     * @throws moodle_exception
     */
    public function __construct() {
        global $DB;

        $this->baseurl    = rtrim(get_config('aiprovider_datacurso', 'apiurl'), '/');
        $this->licensekey = get_config('aiprovider_datacurso', 'licensekey');

        // Detecta automáticamente si es Moodle Workplace validando tool_wp en config_plugins.
        $this->workplace = $DB->record_exists('config_plugins', [
            'plugin' => 'tool_wp',
            'name'   => 'version'
        ]);

        if (empty($this->baseurl) || empty($this->licensekey)) {
            throw new moodle_exception('API baseurl or licensekey not configured');
        }
    }

    /**
     * Build the full URL depending on whether the baseurl uses querystring (?api=).
     *
     * @param string $endpoint The API endpoint.
     * @param array $params Extra query parameters.
     * @return string
     */
    private function build_url(string $endpoint, array $params = []): string {
        if (str_contains($this->baseurl, '?')) {
            $url = $this->baseurl . ltrim($endpoint, '/');
        } else {
            $url = rtrim($this->baseurl, '/') . '/' . ltrim($endpoint, '/');
        }

        if (!empty($params)) {
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($params);
        }

        return $url;
    }

    /**
     * GET request.
     *
     * @param string $endpoint API endpoint.
     * @param array $params Query parameters.
     * @return array
     * @throws moodle_exception
     */
    public function get(string $endpoint, array $params = []): array {
        $url = $this->build_url($endpoint, $params);
        $headers = $this->default_headers();
        return $this->curl_request($url, 'GET', null, $headers);
    }

    /**
     * POST request.
     *
     * @param string $endpoint API endpoint.
     * @param array $data Data to send.
     * @return array
     * @throws moodle_exception
     */
    public function post(string $endpoint, array $data = []): array {
        $url = $this->build_url($endpoint);
        $headers = $this->default_headers(true);
        return $this->curl_request($url, 'POST', $data, $headers);
    }

    /**
     * Default headers (GET/POST).
     *
     * @param bool $ispost Whether it is a POST request.
     * @return array
     */
    private function default_headers(bool $ispost = false): array {
        $headers = [
            "License-Key: {$this->licensekey}",
            "X-Workplace: " . ($this->workplace ? 'true' : 'false'),
        ];

        if ($ispost) {
            $headers[] = 'Content-Type: application/json';
        }

        return $headers;
    }

    /**
     * Execute the cURL request.
     *
     * @param string $url The request URL.
     * @param string $method The request method (GET/POST).
     * @param array|null $data Data to send (only for POST).
     * @param array $headers Request headers.
     * @return array
     * @throws moodle_exception
     */
    private function curl_request(string $url, string $method, ?array $data, array $headers): array {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new moodle_exception("cURL error: $error");
        }

        curl_close($ch);

        if ($httpcode >= 400) {
            throw new moodle_exception("HTTP error $httpcode from $url");
        }

        $decoded = json_decode($response, true);
        if ($decoded === null) {
            throw new moodle_exception("Invalid JSON response from $url: $response");
        }

        return $decoded;
    }
}
