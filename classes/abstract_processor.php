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
use core_ai\process_base;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use moodle_url;
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Psr7\Uri;

/**
 * Base abstract processor for Datacurso AI actions.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class abstract_processor extends process_base {

    /**
     * Get the endpoint URI.
     *
     * @return UriInterface
     */
    abstract protected function get_endpoint(): UriInterface;

    /**
     * 🔹 Construye el cuerpo de la solicitud al servicio IA.
     *
     * Este método lo implementan las clases hijas según el tipo de acción.
     *
     * @param string $userid El ID del usuario.
     * @return array El cuerpo JSON a enviar.
     */
    abstract protected function build_request_body(string $userid): array;

    /**
     * Get the system instructions.
     *
     * @return string
     */
    protected function get_system_instruction(): string {
        return $this->action::get_system_instruction();
    }

    /**
     * Create the request object to send to the OpenAI API.
     *
     * This object contains all the required parameters for the request.
     *
     *
     *
     * @param string $userid The user id.
     * @return RequestInterface The request object to send to the OpenAI API.
     */
    abstract protected function create_request_object(
        string $userid,
    ): RequestInterface;

    /**
     * 🔹 Procesa la respuesta exitosa del API.
     *
     * Cada implementación concreta interpreta su formato de respuesta.
     *
     * @param ResponseInterface $response
     * @return array
     */
    abstract protected function handle_api_success(ResponseInterface $response): array;

    /**
     * 🔹 Ejecuta la consulta al API de Datacurso.
     *
     * @return array
     */
    #[\Override]
    protected function query_ai_api(): array {
        global $USER;

        $licensekey = get_config('aiprovider_datacurso', 'licensekey');
        $userid = $this->action->get_configuration('userid') ?? $USER->id;

        $client = \core\di::get(http_client::class);

        try {
            $response = $client->post(
                $this->get_endpoint(),
                [
                    RequestOptions::HEADERS => [
                        'Content-Type' => 'application/json',
                        'License-Key' => $licensekey,
                    ],
                    RequestOptions::JSON => $this->build_request_body($userid),
                    RequestOptions::HTTP_ERRORS => false, // No lanzar excepción automática.
                ]
            );
        } catch (RequestException $e) {
            return [
                'success' => false,
                'errorcode' => $e->getCode(),
                'errormessage' => $e->getMessage(),
            ];
        }

        $status = $response->getStatusCode();

        if ($status === 200) {
            return $this->handle_api_success($response);
        } else {
            return $this->handle_api_error($response);
        }
    }

    /**
     * 🔹 Maneja respuestas de error del API.
     *
     * @param ResponseInterface $response
     * @return array
     */
    protected function handle_api_error(ResponseInterface $response): array {
        $status = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        $message = 'Error desconocido';
        if (!empty($body)) {
            $decoded = json_decode($body);
            if (isset($decoded->error->message)) {
                $message = $decoded->error->message;
            } else {
                $message = $body;
            }
        }

        return [
            'success' => false,
            'errorcode' => $status,
            'errormessage' => $message,
        ];
    }
}
