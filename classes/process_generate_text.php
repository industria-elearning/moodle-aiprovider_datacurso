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

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Processor for generating text completions.
 * @copyright  Developer <developer@datacurso.com>
 * @package    aiprovider_datacurso
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class process_generate_text extends abstract_processor {

    /**
     * Returns the endpoint URI for this processor.
     */
    #[\Override]
    protected function get_endpoint(): UriInterface {
        return new Uri('https://plugins-ai.datacurso.com/provider/chat/completions');
    }

    /**
     * Build the request body for the API call.
     */
    #[\Override]
    protected function build_request_body(string $userid): array {
        global $USER;

        $finaluserid = $userid ?: $USER->id;

        $systeminstruction = 'Eres un asistente útil';
        $prompt = $this->action->get_configuration('prompttext');

        return [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => $systeminstruction],
                ['role' => 'user', 'content' => $prompt],
            ],
            'userid' => (string)$finaluserid,
        ];
    }

    /**
     * Crea la solicitud HTTP lista para enviar.
     */
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

    /**
     * Procesa la respuesta exitosa de la API.
     */
    #[\Override]
    protected function handle_api_success(ResponseInterface $response): array {
        $body = json_decode($response->getBody()->getContents());

        if (empty($body) || empty($body->choices[0]->message->content)) {
            return [
                'success' => false,
                'error' => 'Respuesta inválida del servicio de IA.',
            ];
        }

        return [
            'success' => true,
            'id' => $body->id ?? null,
            'fingerprint' => $body->system_fingerprint ?? null,
            'generatedcontent' => $body->choices[0]->message->content,
            'finishreason' => $body->choices[0]->finish_reason ?? null,
            'prompttokens' => $body->usage->prompt_tokens ?? null,
            'completiontokens' => $body->usage->completion_tokens ?? null,
            'total_tokens' => $body->usage->total_tokens ?? null,
        ];
    }
}
