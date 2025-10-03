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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');

/**
 * Class datacurso_api_base
 * Base class for interacting with Datacurso APIs.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class datacurso_api_base {

    /** @var string $baseurl The base URL for Datacurso API requests */
    protected $baseurl;

    /** @var string|null $licensekey The license key obtained from Datacurso SHOP */
    protected $licensekey;

    /**
     * Constructor.
     *
     * @param string      $baseurl    The base URL for the API.
     * @param string|null $licensekey The license key obtained from Datacurso SHOP.
     */
    public function __construct(string $baseurl, ?string $licensekey = null) {
        $this->baseurl = $baseurl;
        $this->licensekey = $licensekey ?? get_config('aiprovider_datacurso', 'licensekey');
    }

    /**
     * Sends an HTTP request to the Datacurso API service.
     *
     * @param string     $method HTTP method (GET, POST, PUT, DELETE).
     * @param string     $path   Relative endpoint (e.g., /create-course).
     * @param array|null $body   Request body (for POST/PUT).
     * @return array|null The decoded JSON response, or null on failure.
     * @throws \invalid_parameter_exception If an invalid HTTP method is used.
     */
    public function request(string $method, string $path, ?array $body = []): ?array {
        // Validate HTTP method.
        $allowedmethods = ['GET', 'POST', 'PUT', 'DELETE'];
        if (!in_array(strtoupper($method), $allowedmethods)) {
            throw new \invalid_parameter_exception('Invalid HTTP method: ' . $method);
        }

        // Ensure path starts with "/".
        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        // License key check.
        if (empty($this->licensekey)) {
            debugging('Cannot make this request: no license key available', DEBUG_DEVELOPER);
            return null;
        }

        $curl = new \curl();

        $headers = [
            'Content-Type: application/json',
            'License-Key: ' . $this->licensekey,
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
                    $response = $curl->post($url, json_encode($body), $options);
                    break;
                case 'PUT':
                    $response = $curl->put($url, $body, $options);
                    break;
                case 'DELETE':
                    $response = $curl->delete($url, [], $options);
                    break;
            }
        } catch (\Exception $e) {
            debugging('HTTP request exception: ' . $e->getMessage(), DEBUG_DEVELOPER);
            return null;
        }

        // Handle cURL errors.
        if ($curl->get_errno() !== 0) {
            debugging('cURL error (' . $curl->get_errno() . '): ' . $curl->error, DEBUG_DEVELOPER);
            return null;
        }

        // Validate response.
        if (!$response) {
            debugging('Empty response from Datacurso API', DEBUG_DEVELOPER);
            return null;
        }

        // HTTP code check.
        $httpcode = $curl->get_info()['http_code'] ?? 0;
        if ($httpcode >= 400) {
            debugging("HTTP error {$httpcode} from Datacurso API: {$response}", DEBUG_DEVELOPER);
            return null;
        }

        // Decode JSON response.
        $decodedresponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            debugging('JSON decode error: ' . json_last_error_msg() . '. Response: ' . $response, DEBUG_DEVELOPER);
            return null;
        }

        return $decodedresponse;
    }

    /**
     * Get the configured base URL (for debugging or logging).
     *
     * @return string The base URL.
     */
    public function get_base_url(): string {
        return $this->baseurl;
    }
}
