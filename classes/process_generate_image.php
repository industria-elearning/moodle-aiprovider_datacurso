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

/**
 * Processor for generating images via Datacurso AI provider.
 * @copyright  Developer <developer@datacurso.com>
 * @package    aiprovider_datacurso
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class process_generate_image extends abstract_processor {

    /** @var int Number of images to generate. */
    private int $numberimages = 1;

    #[\Override]
    protected function get_endpoint(): UriInterface {
        return new Uri('https://plugins-ai.datacurso.com/provider/images/generations');
    }

    /**
     * Build the request body for the API call.
     */
    #[\Override]
    protected function build_request_body(string $userid): array {
        global $USER;

        $finaluserid = $userid ?: $USER->id;
        $prompt = $this->action->get_configuration('prompttext');
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
     * Convert aspect ratio to Datacurso-compatible image size.
     */
    private function calculate_size(string $ratio): string {
        return match ($ratio) {
            'square' => '1024x1024',
            'landscape' => '1792x1024',
            'portrait' => '1024x1792',
            default => '1024x1024',
        };
    }

    #[\Override]
    protected function create_request_object(string $userid): RequestInterface {
        $body = json_encode($this->build_request_body($userid));
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

    #[\Override]
    protected function handle_api_success(ResponseInterface $response): array {
        $body = json_decode($response->getBody()->getContents());

        // Validación básica de respuesta.
        if (empty($body) || empty($body->data[0]->b64_json)) {
            return [
                'success' => false,
                'error' => 'Respuesta inválida del servicio de IA (sin imagen).',
            ];
        }

        // Decodificar la imagen base64.
        $imagebase64 = $body->data[0]->b64_json;
        $imagebinary = base64_decode($imagebase64);

        // Guardar la imagen en el área de borradores del usuario.
        $userid = (int)($this->action->get_configuration('userid') ?? 0);
        $file = $this->save_to_draft_area($userid, $imagebinary);

        return [
            'success' => true,
            'sourceurl' => $body->data[0]->url ?? '',
            'revisedprompt' => $body->data[0]->revised_prompt ?? '',
            'draftfile' => $file,
        ];
    }

    /**
     * Guarda la imagen generada en el área de borradores del usuario.
     */
    private function save_to_draft_area(int $userid, string $imagebinary): \stored_file {
        global $CFG;

        require_once("{$CFG->libdir}/filelib.php");

        $filename = 'datacurso_image_' . time() . '.png';
        $tempdst = make_request_directory() . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($tempdst, $imagebinary);

        // Agregar marca de agua antes de guardar.
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

    #[\Override]
    protected function query_ai_api(): array {
        $response = parent::query_ai_api();

        // Moodle espera que draftfile esté definido.
        if (!empty($response['success']) && !empty($response['draftfile'])) {
            return $response;
        }

        // Si no hubo éxito, devolvemos el error procesado.
        return $response;
    }
}
