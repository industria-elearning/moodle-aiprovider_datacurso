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

require('../../../../config.php');

require_login();

$url = new moodle_url('/ai/provider/datacurso/admin/plugin_list.php', []);
$PAGE->set_url($url);
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('link_listplugings', 'aiprovider_datacurso'));

// Ejemplo de plugins.
$pluginslist = [
    [
        'name' => 'Forum IA',
        'description' => 'Extiende los foros con análisis de IA para generar resúmenes automáticos.',
        'installed' => true,
        'url' => 'https://moodle.org/plugins/forum_ia',
    ],
    [
        'name' => 'Datacurso Ratings',
        'description' => 'Permite calificar automáticamente cursos con IA y estadísticas.',
        'installed' => false,
        'url' => 'https://moodle.org/plugins/datacurso_ratings',
    ],
    [
        'name' => 'Course AI',
        'description' => 'Crea cursos, actividades y recursos completos con IA.',
        'installed' => true,
        'url' => 'https://moodle.org/plugins/course_ai',
    ],
];

// Render.
echo $OUTPUT->header();
echo $OUTPUT->render_from_template('aiprovider_datacurso/plugins_list', [
    'plugins' => $pluginslist,
]);
echo $OUTPUT->footer();