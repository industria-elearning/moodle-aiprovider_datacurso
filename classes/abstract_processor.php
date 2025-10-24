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
use Psr\Http\Message\UriInterface;
use GuzzleHttp\Psr7\Uri;

/**
 * Clase base abstracta para los procesadores del proveedor Datacurso AI.
 *
 * Define la estructura común para procesar peticiones y respuestas
 * al servicio externo de IA.
 *
 * @package    aiprovider_datacurso
 * @copyright  Josue
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class abstract_processor extends process_base {

    /**
     * Retorna el endpoint del servicio específico.
     *
     * @return UriInterface
     */
    abstract protected function get_endpoint(): UriInterface;

    /**
     * Construye el cuerpo de la solicitud JSON que se enviará al servicio IA.
     *
     * @param string $userid ID del usuario que solicita la acción.
     * @return array Estructura de datos que será enviada como JSON.
     */
    abstract protected function build_request_body(string $userid): array;

    /**
     * Crea el objeto HTTP Request listo para enviar al servicio.
     *
     * @param string $userid ID del usuario.
     * @return RequestInterface Objeto de solicitud HTTP.
     */
    abstract protected function create_request_object(string $userid): RequestInterface;

    /**
     * Procesa una respuesta exitosa desde el servicio IA.
     *
     * @param ResponseInterface $response Respuesta HTTP.
     * @return array Datos estructurados con la información procesada.
     */
    abstract protected function handle_api_success(ResponseInterface $response): array;

    /**
     * Obtiene la instrucción del sistema definida para la acción.
     *
     * @return string Instrucción del sistema.
     */
    protected function get_system_instruction(): string {
        return $this->action::get_system_instruction();
    }

    /**
     * Ejecuta la consulta al servicio externo Datacurso AI.
     *
     * @return array Respuesta procesada con éxito o error.
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
                    RequestOptions::HTTP_ERRORS => false,
                ]
            );
        } catch (RequestException $e) {
            return [
                'success' => false,
                'errorcode' => (int)($e->getCode() ?: 500),
                'errormessage' => $e->getMessage(),
            ];
        }

        $status = (int)$response->getStatusCode();

        if ($status === 200) {
            return $this->handle_api_success($response);
        }

        return $this->handle_api_error($response);
    }

    /**
     * Maneja las respuestas de error devueltas por el servicio IA.
     *
     * @param ResponseInterface $response Respuesta HTTP del servicio.
     * @return array Datos estructurados del error.
     */
    protected function handle_api_error(ResponseInterface $response): array {
        $status = (int)$response->getStatusCode();
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
            'errorcode' => $status ?: 500,
            'errormessage' => $message,
        ];
    }
}
