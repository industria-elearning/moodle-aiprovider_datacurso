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

use core_ai\aiactions;

/**
 * Provider class for DataCurso.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider extends \core_ai\provider {

    /**
     * Constructor.
     */
    public function __construct() {
        // Here you could load configurations in the future (currently empty).
    }

    /**
     * Get the list of actions that this provider supports.
     *
     * @return array
     */
    public function get_action_list(): array {
        return [
            \core_ai\aiactions\generate_text::class,
            \core_ai\aiactions\generate_image::class,
            \core_ai\aiactions\summarise_text::class,
        ];
    }

    /**
     * Check if the provider is configured.
     *
     * @return bool
     */
    public function is_provider_configured(): bool {
        $licensekey = get_config('aiprovider_datacurso', 'licensekey');
        // Provider is configured if we have license key.
        return !empty($licensekey);
    }

    /**
     * Check if a request is allowed.
     *
     * @param aiactions\base $action
     * @return array|bool
     */
    public function is_request_allowed(aiactions\base $action): array|bool {
        global $USER;
        // Check basic capability.
        if (!has_capability('aiprovider/datacurso:use', \context_system::instance(), $USER)) {
            return false;
        }
        return true;
    }

    /**
     * Get any action settings for this provider.
     *
     * @param string $action The action class name.
     * @param \admin_root $ADMIN The admin root object.
     * @param string $section The section name.
     * @param bool $hassiteconfig Whether the current user has moodle/site:config capability.
     * @return array
     */
    public function get_action_settings(
        string $action,
        \admin_root $ADMIN,
        string $section,
        bool $hassiteconfig
    ): array {
        // No settings added yet.
        return [];
    }

    /**
     * 🔹 Retorna los servicios disponibles del proveedor IA.
     *
     * @return array
     */
    public static function get_services(): array {
        return [
            ['id' => 'course_ai', 'name' => 'Course AI'],
            ['id' => 'local_datacurso_ratings', 'name' => 'Rating AI'],
            ['id' => 'local_forum_ai', 'name' => 'Forum AI'],
            ['id' => 'local_assign_ai', 'name' => 'Assign AI'],
            ['id' => 'tutor_ai', 'name' => 'Tutor IA'],
            ['id' => 'local_socialcert', 'name' => 'Certificate AI'],
        ];
    }

    /**
     * 🔹 Retorna las acciones disponibles del proveedor IA.
     *
     * @return array
     */
    public static function get_actions(): array {
        return [
            ['id' => 'generate_text', 'name' => 'Generar texto con IA'],
            ['id' => '/assign/answer', 'name' => 'Generar revisión para tarea con IA'],
            ['id' => '/planning/plan-course/start', 'name' => 'Crear plan de curso con IA'],
            ['id' => '/planning/plan-course/execute', 'name' => 'Crear un curso completo con IA'],
            ['id' => '/rating/general', 'name' => 'Generar análisis global de reporte con IA'],
            ['id' => '/rating/course', 'name' => 'Generar análisis del curso con IA'],
            ['id' => '/rating/query', 'name' => 'Generar análisis de una actividad con IA'],
            ['id' => '/resources/create-mod', 'name' => 'Generar una actividad con IA'],
            ['id' => '/forum/chat', 'name' => 'Generar respuesta foro con IA'],
            ['id' => '/context/upload', 'name' => 'Proporcionar silabo a la IA para la creación del curso'],
            ['id' => '/resources/create-mod/stream', 'name' => 'Generar una actividad con IA'],
            ['id' => '/certificate/answer', 'name' => 'Generar respuesta para certificado'],
        ];
    }
}
