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
    /**
     * Constructor.
     *
     * @param string|null $licensekey The license key obtained from Datacurso SHOP.
     */
    public function __construct(?string $licensekey = null) {
        parent::__construct('https://plugins-ai.datacurso.com', $licensekey);
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
