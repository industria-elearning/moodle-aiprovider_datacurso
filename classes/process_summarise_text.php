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

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

/**
 * Processor for summarising text via Datacurso AI provider.
 * @copyright  Developer <developer@datacurso.com>
 * @package    aiprovider_datacurso
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class process_summarise_text extends process_generate_text {

    /**
     * Endpoint del servicio (igual que el de generación de texto).
     */
    #[\Override]
    protected function get_endpoint(): UriInterface {
        return new Uri('https://plugins-ai.datacurso.com/provider/chat/completions');
    }

    /**
     * Instrucción del sistema específica para resúmenes.
     */
    #[\Override]
    protected function build_request_body(string $userid): array {
        global $USER;

        $finaluserid = $userid ?: $USER->id;
        $prompt = $this->action->get_configuration('prompttext');

        // Aquí definimos la instrucción de sistema especial para resúmenes.
        $systeminstruction = 'Eres un asistente experto en resumir textos de manera clara, breve y coherente.';

        return [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => $systeminstruction],
                ['role' => 'user', 'content' => $prompt],
            ],
            'userid' => (string)$finaluserid,
        ];
    }
}
