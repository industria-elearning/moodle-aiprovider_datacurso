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
 * Page to render the AI consumption information.
 *
 * This class provides the context data for the Mustache template
 * used to render the AI consumption page.
 *
 * @package    aiprovider_datacurso
 * @category   output
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class consumption_page implements renderable, templatable {

    /**
     * Export data for the template.
     *
     * @param renderer_base $output The renderer in use.
     * @return array Data to pass to the Mustache.
     */
    public function export_for_template(renderer_base $output): array {
        return [];
    }
}
