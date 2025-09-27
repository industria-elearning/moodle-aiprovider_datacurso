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
 * HTTP client for the Datacurso AI services API.
 *
 * Note: This URL is different from the token management API URL
 * configured in settings. This is the fixed endpoint for AI services.
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
        // If no token is provided, use the default one (for testing purposes).
        $this->token = $token ?? 'xryQAHKm6sWafYoQRZmZX6VTY0UVqjUQuzWwUlMwITQYC7THAZbDoUFE81Mg0raw';
    }

    /**
     * Generic method to send HTTP requests to the service.
     *
     * @param string $method GET, POST, PUT, DELETE
     * @param string $path Relative endpoint (e.g., /health)
     * @param array|null $body Body data in case of POST/PUT
     * @return array|null Response data or null on failure
     * @throws \invalid_parameter_exception
     */
    public function request(string $method, string $path, ?array $body = null): ?array {
        // Validate HTTP method.
        $allowedmethods = ['GET', 'POST', 'PUT', 'DELETE'];
        if (!in_array(strtoupper($method), $allowedmethods)) {
            throw new \invalid_parameter_exception('Invalid HTTP method: ' . $method);
        }

        // Validate path format.
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        // Check if we have a token (should always be available during testing).
        if (empty($this->token)) {
            debugging('Cannot make API request: no token available', DEBUG_DEVELOPER);
            return null;
        }

        $curl = new \curl();

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token,
        ];

        $options = [
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_HTTPHEADER' => $headers,
            'CURLOPT_TIMEOUT' => 30,
            'CURLOPT_CONNECTTIMEOUT' => 10,
        ];

        $url = $this->baseurl . $path;
        $response = null;

        try {
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
        } catch (\Exception $e) {
            debugging('HTTP request exception: ' . $e->getMessage(), DEBUG_DEVELOPER);
            return null;
        }

        // Check for cURL errors.
        if ($curl->get_errno() !== 0) {
            debugging('cURL error (' . $curl->get_errno() . '): ' . $curl->error, DEBUG_DEVELOPER);
            return null;
        }

        // Check if we got a response.
        if (!$response) {
            debugging('Empty response from Datacurso AI services', DEBUG_DEVELOPER);
            return null;
        }

        // Check HTTP status code.
        $httpcode = $curl->get_info()['http_code'] ?? 0;
        if ($httpcode >= 400) {
            debugging("HTTP error {$httpcode} from Datacurso AI services: {$response}", DEBUG_DEVELOPER);
            return null;
        }

        // Decode JSON response.
        $decodedresponse = json_decode($response, true);

        // Check for JSON decode errors.
        if (json_last_error() !== JSON_ERROR_NONE) {
            debugging('JSON decode error: ' . json_last_error_msg() . '. Response: ' . $response, DEBUG_DEVELOPER);
            return null;
        }

        return $decodedresponse;
    }

    /**
     * Checks the service status.
     *
     * @return array|null Service status or null on failure
     */
    public function get_health(): ?array {
        return $this->request('GET', '/health');
    }

    /**
     * Check if the service is available and properly configured.
     *
     * @return bool True if service is available
     */
    public function is_available(): bool {
        if (empty($this->token)) {
            return false;
        }

        $health = $this->get_health();
        return $health !== null;
    }

    /**
     * Get the configured base URL for debugging purposes.
     *
     * @return string The base URL
     */
    public function get_base_url(): string {
        return $this->baseurl;
    }
}
