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

/**
 * Class ai_services_api
 *
 * HTTP client for the Plugins AI API service.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ai_services_api {

    /** @var string */
    private $baseurl = 'https://plugins-ai-dev.datacurso.com';

    /** @var string */
    private $token;

    /**
     * Constructor.
     *
     * @param string|null $token Bearer authentication token
     */
    public function __construct(?string $token = null) {
        // If no token is provided, use the default one (mock example).
        $this->token = $token ?? 'xryQAHKm6sWafYoQRZmZX6VTY0UVqjUQuzWwUlMwITQYC7THAZbDoUFE81Mg0raw';
    }

    /**
     * Generic method to send HTTP requests to the service.
     *
     * @param string $method GET, POST, PUT, DELETE
     * @param string $path Relative endpoint (e.g., /health)
     * @param array|null $body Body data in case of POST/PUT
     * @return array|null
     */
    public function request(string $method, string $path, ?array $body = null): ?array {
        $curl = new \curl();

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token,
        ];

        $options = [
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_HTTPHEADER' => $headers,
        ];

        $url = $this->baseurl . $path;
        $response = null;

        switch (strtoupper($method)) {
            case 'GET':
                $response = $curl->get($url, [], $options);
                break;
            case 'POST':
                $response = $curl->post($url, json_encode($body ?? []), $options);
                break;
            case 'PUT':
                $response = $curl->put($url, json_encode($body ?? []), $options);
                break;
            case 'DELETE':
                $response = $curl->delete($url, [], $options);
                break;
        }

        if (!$response) {
            return null;
        }

        return json_decode($response, true);
    }

    /**
     * Checks the service status.
     *
     * @return array|null
     */
    public function get_health(): ?array {
        return $this->request('GET', '/health');
    }
}
