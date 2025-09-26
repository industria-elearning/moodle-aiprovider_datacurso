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
 * Class ai_course_api
 *
 * Cliente HTTP para el servicio FastAPI de planificación y recursos de curso.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ai_course_api {

    /** @var string */
    private $baseurl = 'https://moodle-langgraph-dev';

    /** @var string|null */
    private $token;

    /**
     * Constructor.
     *
     * @param string|null $token Token de autenticación Bearer
     */
    public function __construct(?string $token = null) {
        // Si no se pasa token, usar el definido por defecto (ejemplo de mock).
        $this->token = $token ?? 'xryQAHKm6sWafYoQRZmZX6VTY0UVqjUQuzWwUlMwITQYC7THAZbDoUFE81Mg0raw';
    }

    /**
     * Método genérico para enviar peticiones HTTP al servicio.
     *
     * @param string $method GET, POST, PUT, DELETE
     * @param string $path Endpoint relativo (ej: /planning/plan-course)
     * @param array|null $body Datos del cuerpo en caso de POST/PUT
     * @param bool $authrequired Indica si este endpoint requiere autenticación
     * @return array|null
     */
    public function request(string $method, string $path, ?array $body = null, bool $authrequired = true): ?array {
        $curl = new \curl();

        $headers = [
            'Content-Type: application/json',
        ];

        if ($authrequired && !empty($this->token)) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }

        $options = [
            'CURLOPT_RETURNTRANSFER' => true,
            'CURLOPT_HTTPHEADER' => $headers,
        ];

        $url = $this->baseurl . $path;
        $response = null;

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

        if (!$response) {
            return null;
        }

        return json_decode($response, true);
    }

    /**
     * Verifica el estado del servicio (endpoint público, sin auth).
     *
     * @return array|null
     */
    public function get_health(): ?array {
        return $this->request('GET', '/health', null, false);
    }
}
