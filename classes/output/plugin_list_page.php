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

namespace aiprovider_datacurso\output;

use renderable;
use templatable;
use renderer_base;

/**
 * Page to render the list of installed AI plugins.
 *
 * Provides the context data for the Mustache template
 * that displays a list of plugins related to AI.
 *
 * @package    aiprovider_datacurso
 * @category   output
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class plugin_list_page implements renderable, templatable {

    /**
     * List of plugins to be displayed.
     *
     * @var array
     */
    private array $plugins;

    /**
     * Constructor.
     *
     * @param array $plugins Array of plugin data.
     */
    public function __construct(array $plugins) {
        $this->plugins = $plugins;
    }

    /**
     * Export data for the Mustache template.
     *
     * @param renderer_base $output The renderer instance.
     * @return array Data to be used in the template.
     */
    public function export_for_template(renderer_base $output): array {
        return [
            'plugins' => $this->plugins,
        ];
    }
}
