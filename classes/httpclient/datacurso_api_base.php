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
     * @param string $baseurl The base URL for Datacurso API requests.
     * @param string|null $licensekey The license key obtained from Datacurso SHOP.
     */
    public function __construct(string $baseurl, ?string $licensekey = null) {
        $this->baseurl = $baseurl;
        $this->licensekey = $licensekey ?? get_config('aiprovider_datacurso', 'licensekey');
    }

    /**
     * Generic handler for HTTP calls to Datacurso API.
     *
     * @param string $method HTTP method (GET, POST, PUT, DELETE, UPLOAD).
     * @param string $path   Relative endpoint (starting with "/").
     * @param mixed  $payload Data for request (array, string, or multipart).
     * @param array  $headers Extra headers if needed.
     * @return array|null
     * @throws \Exception
     */
    protected function send_request(string $method, string $path, $payload = [], array $headers = []): ?array {
        global $USER, $CFG;
        if (empty($this->licensekey)) {
            debugging('Cannot make this request: no license key available', DEBUG_DEVELOPER);
            return null;
        }

        if (!str_starts_with($path, '/')) {
            $path = '/' . $path;
        }

        $curl = new \curl();
        $baseheaders = [
            'License-Key: ' . $this->licensekey,
        ];

        $headers = array_merge($baseheaders, $headers);

        $options = [
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_HTTPHEADER' => $headers,
            'CURLOPT_TIMEOUT' => 60,
            'CURLOPT_CONNECTTIMEOUT' => 15,
        ];

        $url = $this->baseurl . $path;
        $response = null;

        $defaultpayload = [
            'site_id' => md5($CFG->wwwroot),
            'userid' => $payload['userid'] ?? $USER->id,
            'timezone' => \core_date::get_user_timezone(),
            'lang' => $payload['lang'] ?? current_language(),
        ];
        try {
            switch (strtoupper($method)) {
                case 'GET':
                    $response = $curl->get($url, $payload, $options);
                    break;
                case 'POST':
                    $payload = array_merge($payload, $defaultpayload);
                    $response = $curl->post($url, json_encode($payload), $options);
                    break;
                case 'PUT':
                    $payload = array_merge($payload, $defaultpayload);
                    $response = $curl->put($url, $payload, $options);
                    break;
                case 'DELETE':
                    $response = $curl->delete($url, $payload, $options);
                    break;
                case 'UPLOAD':
                    $payload = array_merge($payload, $defaultpayload);
                    $response = $curl->post($url, $payload, $options);
                    break;
                default:
                    throw new \coding_exception('Invalid HTTP method: ' . $method);
            }
        } catch (\Exception $e) {
            debugging('HTTP request exception: ' . $e->getMessage(), DEBUG_DEVELOPER);
            throw $e;
        }

        if (!$response) {
            debugging('Empty response from Datacurso API', DEBUG_DEVELOPER);
            throw new \Exception('Could not get response from Datacurso API');
        }

        if ($curl->error) {
            debugging('cURL error (' . $curl->error . ')', DEBUG_DEVELOPER);
            throw new \Exception('Error in Datacurso API request: ' . $curl->error);
        }

        $httpcode = $curl->get_info()['http_code'] ?? 0;
        if ($httpcode >= 400) {
            debugging("HTTP error {$httpcode} from Datacurso API: {$response}", DEBUG_DEVELOPER);
            throw new \Exception("HTTP error {$httpcode}");
        }

        $decodedresponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            debugging('JSON decode error: ' . json_last_error_msg() . '. Response: ' . $response, DEBUG_DEVELOPER);
            throw new \Exception('Error processing response from Datacurso API');
        }

        return $decodedresponse;
    }

    /**
     * Standard JSON API call.
     *
     * @param string $method HTTP method (GET, POST, PUT, DELETE, UPLOAD).
     * @param string $path   Relative endpoint. Example: '/create-course'.
     * @param array $body Data for request.
     * @return array|null
     * @throws \Exception
     */
    public function request(string $method, string $path, array $body = []): ?array {
        $headers = ['Content-Type: application/json'];
        return $this->send_request($method, $path, $body, $headers);
    }

    /**
     * Upload a file using multipart/form-data.
     *
     * @param string $path   Relative endpoint. Example: '/upload-file'.
     * @param string $filepath Path to the file to upload.
     * @param string $mimetype MIME type of the file.
     * @param string $filename Name of the file to upload.
     * @param array  $extraparams Extra parameters for the request.
     * @return array|null
     * @throws \Exception
     */
    public function upload_file(string $path, string $filepath, $mimetype = null,
            $filename = null, array $extraparams = []): ?array {
        if (!file_exists($filepath)) {
            $filename = basename($filepath);
            throw new \coding_exception("File not found: {$filename}");
        }

        $postdata = array_merge($extraparams, [
            'file' => new \CURLFile($filepath, $mimetype, $filename),
        ]);

        return $this->send_request('UPLOAD', $path, $postdata);
    }
}

