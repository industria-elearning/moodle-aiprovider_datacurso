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
 * HTTP client for the Datacurso AI services API.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ai_services_api extends datacurso_api_base {
    /** Default base URL for the standard DataCurso AI service. */
    private const DEFAULT_BASE_URL = 'http://plugins-ai-server:8000';

    /** Default base URL for the EU-hosted DataCurso AI service. */
    private const DEFAULT_BASE_URL_EU = 'http://plugins-ai-server:8000';

    /**
     * Constructor.
     *
     * @param string|null $licensekey The license key obtained from Datacurso SHOP.
     * @param string|null $baseurl Optional standard-region base URL to override the default endpoint.
     * @param string|null $baseurleu Optional EU-region base URL to override the default endpoint.
     */
    public function __construct(?string $licensekey = null, ?string $baseurl = null, ?string $baseurleu = null) {
        global $CFG;

        if ($this->is_for_ue()) {
            $finalbaseurl    = $baseurleu ?? self::DEFAULT_BASE_URL_EU;
        } else {
            $finalbaseurl    = $baseurl ?? self::DEFAULT_BASE_URL;
        }

        parent::__construct($finalbaseurl, $licensekey);
    }

    /**
     * Build the streaming URL for a given session ID, adjusting base URL.
     *
     * @param string $sessionid
     * @return string streaming URL
     */
    public function get_streaming_url_for_session(string $sessionid): string {
        // Build streaming URL with session ID.
        $baseurl = rtrim($this->baseurl, '/');

        return $baseurl . '/chat/stream?session_id=' . urlencode($sessionid);
    }
}
