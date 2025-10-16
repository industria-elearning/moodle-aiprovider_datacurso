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
 * Provider class for DataCurso AI integration.
 * @package    aiprovider_datacurso
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider extends \core_ai\provider {

    /** @var string License key for Datacurso API. */
    private string $licensekey;

    /**
     * Constructor.
     */
    public function __construct() {
        // Cargamos el licensekey desde la configuración del plugin.
        $this->licensekey = get_config('aiprovider_datacurso', 'licensekey');
    }

    /**
     * Get the list of AI actions supported by this provider.
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
     * Check if the provider is configured properly.
     *
     * @return bool
     */
    public function is_provider_configured(): bool {
        // El proveedor está configurado si el licensekey no está vacío.
        return !empty($this->licensekey);
    }

    /**
     * Check if a request is allowed for this provider.
     *
     * @param aiactions\base $action
     * @return array|bool
     */
    public function is_request_allowed(aiactions\base $action): array|bool {
        global $USER;

        // Solo los usuarios con la capability definida pueden usar el proveedor.
        if (!has_capability('aiprovider/datacurso:use', \context_system::instance(), $USER)) {
            return false;
        }

        return true;
    }

    /**
     * Add authentication headers to a request.
     *
     * @param \Psr\Http\Message\RequestInterface $request
     * @return \Psr\Http\Message\RequestInterface
     */
    public function add_authentication_headers(\Psr\Http\Message\RequestInterface $request): \Psr\Http\Message\RequestInterface {
        // Añade el licensekey al header Authorization.
        return $request->withAddedHeader('Authorization', "Bearer {$this->licensekey}");
    }

    /**
     * Get any admin settings available per AI action.
     *
     * @param string $action The action class name.
     * @param \admin_root $ADMIN The admin root object.
     * @param string $section The section name.
     * @param bool $hassiteconfig Whether the current user can configure site settings.
     * @return array
     */
    public function get_action_settings(
        string $action,
        \admin_root $ADMIN,
        string $section,
        bool $hassiteconfig
    ): array {
        $actionname = substr($action, (strrpos($action, '\\') + 1));
        $settings = [];

        // Configuración para generación de texto o resumen.
        if ($actionname === 'generate_text' || $actionname === 'summarise_text') {
            $settings[] = new \admin_setting_configtextarea(
                "aiprovider_datacurso/action_{$actionname}_instruction",
                new \lang_string("action:{$actionname}:instruction", 'aiprovider_datacurso'),
                new \lang_string("action:{$actionname}:instruction_desc", 'aiprovider_datacurso'),
                $action::get_system_instruction(),
                PARAM_TEXT
            );
        }

        return $settings;
    }

    /**
     * Return all available AI services for this provider.
     *
     * @return array
     */
    public static function get_services(): array {
        return [
            ['id' => 'course_ai', 'name' => 'Course AI'],
            ['id' => 'local_datacurso_ratings', 'name' => 'Rating AI'],
            ['id' => 'local_forum_ai', 'name' => 'Forum AI'],
            ['id' => 'local_assign_ai', 'name' => 'Assign AI'],
            ['id' => 'tutor_ai', 'name' => 'Tutor AI'],
            ['id' => 'local_socialcert', 'name' => 'Certificate AI'],
            ['id' => 'provider_proxy', 'name' => 'Provider'],
        ];
    }

    /**
     * Return all available AI actions for this provider.
     *
     * @return array
     */
    public static function get_actions(): array {
        return [
            ['id' => '/provider/chat/completions', 'name' => get_string('generate_text', 'aiprovider_datacurso')],
            ['id' => '/provider/images/generations', 'name' => get_string('generate_image', 'aiprovider_datacurso')],
            ['id' => '/course/execute', 'name' => get_string('generate_creation_course', 'aiprovider_datacurso')],
            ['id' => '/course/start', 'name' => get_string('generate_plan_course', 'aiprovider_datacurso')],
            ['id' => '/resources/create-mod', 'name' => get_string('generate_activitie', 'aiprovider_datacurso')],
            ['id' => '/assign/answer', 'name' => get_string('generate_assign_answer', 'aiprovider_datacurso')],
            ['id' => '/forum/chat', 'name' => get_string('generate_forum_chat', 'aiprovider_datacurso')],
            ['id' => '/rating/general', 'name' => get_string('generate_analysis_general', 'aiprovider_datacurso')],
            ['id' => '/rating/course', 'name' => get_string('generate_analysis_course', 'aiprovider_datacurso')],
            ['id' => '/rating/query', 'name' => get_string('generate_analysis_comments', 'aiprovider_datacurso')],
            ['id' => '/context/upload', 'name' => get_string('read_context_course', 'aiprovider_datacurso')],
            ['id' => '/context/upload-model-context', 'name' => get_string('read_context_course_model', 'aiprovider_datacurso')],
            ['id' => '/resources/create-mod/stream', 'name' => get_string('generate_activitie', 'aiprovider_datacurso')],
            ['id' => '/certificate/answer', 'name' => get_string('generate_certificate_answer', 'aiprovider_datacurso')],
            ['id' => '/story/analysis', 'name' => get_string('generate_analysis_story_student', 'aiprovider_datacurso')],
        ];
    }
}
