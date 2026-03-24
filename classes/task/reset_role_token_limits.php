<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace aiprovider_datacurso\task;

use aiprovider_datacurso\local\role_token_limit_manager;

/**
 * Scheduled task to process recurring role token limit resets.
 *
 * @package    aiprovider_datacurso
 * @copyright  2026 Datacurso
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class reset_role_token_limits extends \core\task\scheduled_task {
    /**
     * Get task display name.
     *
     * @return string
     */
    public function get_name(): string {
        return get_string('task_reset_role_token_limits', 'aiprovider_datacurso');
    }

    /**
     * Execute task.
     *
     * @return void
     */
    public function execute(): void {
        role_token_limit_manager::process_recurring_resets();
    }
}
