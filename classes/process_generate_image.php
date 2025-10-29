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

namespace aiprovider_datacurso;

use core\http_client;
use core_ai\ai_image;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');

/**
 * Processor for generating images via Datacurso AI provider.
 *
 * @package    aiprovider_datacurso
 * @copyright  Developer <developer@datacurso.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class process_generate_image extends abstract_processor {
    /** @var int Number of images to generate. */
    private int $numberimages = 1;

    /**
     * Returns the endpoint URI for this processor.
     *
     * @return UriInterface
     */
    #[\Override]
    protected function get_endpoint(): UriInterface {
        return new Uri('https://plugins-ai.datacurso.com/provider/images/generations');
    }

    /**
     * Build the request body for the API call.
     *
     * @param string $userid User ID for the request
     * @return array Request body parameters
     * @throws \moodle_exception If prompt is empty
     */
    #[\Override]
    protected function build_request_body(string $userid): array {
        global $USER;

        $finaluserid = $userid ?: $USER->id;
        $prompt = $this->action->get_configuration('prompttext');

        // Validate that prompt is not empty.
        if (empty($prompt)) {
            throw new \moodle_exception(
                'emptyprompt',
                'aiprovider_datacurso',
                '',
                null,
                'Image generation requires a prompt text'
            );
        }

        $aspectratio = $this->action->get_configuration('aspectratio') ?? 'square';
        $size = $this->calculate_size($aspectratio);

        return [
            'prompt' => $prompt,
            'n' => $this->numberimages,
            'size' => $size,
            'userid' => (string)$finaluserid,
        ];
    }

    /**
     * Convert aspect ratio selector to the Datacurso image size expected by the API.
     *
     * @param string $ratio Aspect ratio configuration value
     * @return string Datacurso size token (e.g. 1024x1024)
     */
    private function calculate_size(string $ratio): string {
        return match ($ratio) {
            'square' => '1024x1024',
            'landscape' => '1792x1024',
            'portrait' => '1024x1792',
            default => '1024x1024',
        };
    }

    /**
     * Create the HTTP request object ready to send.
     *
     * @param string $userid User ID for the request
     * @return RequestInterface PSR-7 request object
     */
    #[\Override]
    protected function create_request_object(string $userid): RequestInterface {
        $body = json_encode($this->build_request_body($userid));

        // Debug: Log the request body for development purposes.
        debugging('Image generation request body: ' . $body, DEBUG_DEVELOPER);

        $licensekey = get_config('aiprovider_datacurso', 'licensekey');

        return new Request(
            'POST',
            $this->get_endpoint(),
            [
                'Content-Type' => 'application/json',
                'License-Key' => $licensekey,
                'User-Agent' => 'moodle-aiprovider-datacurso',
            ],
            $body
        );
    }

    /**
     * Handle successful API response.
     *
     * @param ResponseInterface $response PSR-7 response object
     * @return array Response data with success status and file information
     */
    #[\Override]
    protected function handle_api_success(ResponseInterface $response): array {
        $body = json_decode($response->getBody()->getContents());

        // Validate response structure.
        if (empty($body) || empty($body->data[0])) {
            debugging('Invalid API response: missing data array', DEBUG_DEVELOPER);
            return [
                'success' => false,
                'errorcode' => 400,
                'errormessage' => get_string('responseinvalidaimage', 'aiprovider_datacurso'),
            ];
        }

        $imageurl = $body->data[0]->url ?? null;

        // Ensure at least one image format is provided.
        if (empty($imageurl)) {
            debugging('Invalid API response: no valid image format provided', DEBUG_DEVELOPER);
            return [
                'success' => false,
                'errorcode' => 400,
                'errormessage' => get_string('responseinvalidaimage', 'aiprovider_datacurso'),
            ];
        }

        $userid = (int)($this->action->get_configuration('userid') ?? 0);
        $file = $this->download_file($imageurl, $userid);

        // Verify that file was successfully created.
        if (!$file instanceof \stored_file) {
            return [
                'success' => false,
                'errorcode' => 500,
                'errormessage' => get_string('responseinvalidaimagecreate', 'aiprovider_datacurso'),
            ];
        }

        return [
            'success' => true,
            'errorcode' => 200,
            'sourceurl' => $imageurl ?? '',
            'revisedprompt' => $body->data[0]->revised_prompt ?? '',
            'draftfile' => $file,
        ];
    }

    /**
     * Download a file from Datacurso API.
     *
     * @param string $endpoint Relative endpoint (starting with "/").
     * @param string $filename The name of the file to download.
     * @param array $filerecord File record options as accepted by create_file_from_url(); defaults to storing in draft user area.
     * @return \stored_file|null The downloaded file.
     * @throws \Exception If the file cannot be created.
     */
    public function download_file($imageurl, $userid): ?\stored_file {
        $path = parse_url($imageurl, PHP_URL_PATH);

        $filename = basename($path);

        $draftid = file_get_unused_draft_itemid();

        $fs = get_file_storage();
        $context = \context_user::instance($userid);
        $fileinfo = [
            'contextid' => $context->id,
            'component' => 'user',
            'filearea' => 'draft',
            'itemid' => $draftid,
            'filepath' => '/',
            'filename' => $filename,
        ];

        $file = $fs->create_file_from_url($fileinfo, $imageurl, [], true);
        return $file;
    }

    /**
     * Store the generated image into the user's draft file area.
     *
     * @param int $userid User ID that will own the draft file
     * @param string $imagebinary Raw PNG binary string
     * @return \stored_file Draft file reference
     * @throws \file_exception If file creation fails
     */
    private function save_to_draft_area(int $userid, string $imagebinary): \stored_file {
        global $CFG;

        require_once("{$CFG->libdir}/filelib.php");

        $filename = 'datacurso_image_' . time() . '.png';
        $tempdst = make_request_directory() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($tempdst, $imagebinary);

        // Add watermark before saving to draft area.
        $image = new ai_image($tempdst);
        $image->add_watermark()->save();

        $fileinfo = (object)[
            'contextid' => \context_user::instance($userid)->id,
            'component' => 'user',
            'filearea' => 'draft',
            'itemid' => file_get_unused_draft_itemid(),
            'filepath' => '/',
            'filename' => $filename,
        ];

        $fs = get_file_storage();
        return $fs->create_file_from_pathname($fileinfo, $tempdst);
    }

    /**
     * Query the AI API and validate response.
     *
     * @return array Response data with success status and file information
     */
    #[\Override]
    protected function query_ai_api(): array {
        $response = parent::query_ai_api();

        // Moodle expects draftfile to be defined for image generation.
        if (!empty($response['success']) && !empty($response['draftfile'])) {
            return $response;
        }

        // If not successful, return error process.
        return $response;
    }
}
