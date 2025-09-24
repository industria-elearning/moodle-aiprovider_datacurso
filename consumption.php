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

/**
 * Página de historial de consumo de tokens.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');
require_login();

// URL y contexto.
$url = new moodle_url('/ai/provider/datacurso/consumption.php');
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_heading($SITE->fullname);
$PAGE->set_title(get_string('link_consumptionhistory', 'aiprovider_datacurso'));

// Datos de ejemplo (más adelante aquí consumirás tu API).
$consumos = [
    [
        'id_consumo' => 1,
        'id_usuario' => 123,
        'accion' => 'consulta_api',
        'cantidad_tokens' => 5,
        'saldo_restante' => 95,
        'fecha' => '2023-09-15',
    ],
    [
        'id_consumo' => 2,
        'id_usuario' => 456,
        'accion' => 'generacion_texto',
        'cantidad_tokens' => 12,
        'saldo_restante' => 83,
        'fecha' => '2023-09-16',
    ],
];

// Contexto que se pasa al mustache.
$templatecontext = [
    'consumos' => $consumos
];

// Render.
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('aiprovider_datacurso/consumption', $templatecontext);
echo $OUTPUT->footer();
