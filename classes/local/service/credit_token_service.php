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

namespace aiprovider_datacurso\local\service;

use aiprovider_datacurso\local\role_token_limit_manager;
use aiprovider_datacurso\local\user_token_limit_manager;

/**
 * Service class for managing credit tokens.
 *
 * @package    aiprovider_datacurso
 * @copyright  2025 Josue <https://datacurso.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class credit_token_service {
    /**
     * Get the available credits balance for assignment.
     *
     * @return array
     */
    public static function get_credits_balance(): array {
        $pool = user_token_limit_manager::get_license_pool();
        if (($pool['status'] ?? 'error') !== 'success') {
            return [
                'status' => 'error',
                'balance' => 0,
                'availabletoassign' => 0,
                'message' => get_string('errorgetbalancecredits', 'aiprovider_datacurso'),
            ];
        }

        return [
            'status' => 'success',
            'balance' => (int)($pool['licensebalance'] ?? 0),
            'availabletoassign' => (int)($pool['availabletoassign'] ?? 0),
            'message' => '',
        ];
    }

    /**
     * Reset usage counters for a user token limit record.
     *
     * @param int $id Record ID to reset.
     * @return array
     */
    public static function reset_user_token_usage(int $id): array {
        if ($id > 0) {
            $ok = user_token_limit_manager::reset_usage($id);
            if ($ok) {
                return [
                    'success' => true,
                    'message' => get_string('usertokenlimit_reset_done', 'aiprovider_datacurso'),
                ];
            }
        }

        return [
            'success' => false,
            'message' => get_string('usertokenlimit_reset_failed', 'aiprovider_datacurso'),
        ];
    }

    /**
     * Delete a user token limit record.
     *
     * @param int $id Record ID to delete.
     * @return array
     */
    public static function delete_user_token_limit(int $id): array {
        if ($id > 0) {
            user_token_limit_manager::delete($id);
            return [
                'success' => true,
                'message' => get_string('usertokenlimit_deleted', 'aiprovider_datacurso'),
            ];
        }

        return [
            'success' => false,
            'message' => get_string('usertokenlimit_delete_failed', 'aiprovider_datacurso'),
        ];
    }

    /**
     * Reset usage counters for a role token limit record.
     *
     * @param int $id Record ID to reset.
     * @return array
     */
    public static function reset_role_token_usage(int $id): array {
        if ($id > 0) {
            $ok = role_token_limit_manager::reset_usage($id);
            if ($ok) {
                return [
                    'success' => true,
                    'message' => get_string('usertokenlimit_reset_done', 'aiprovider_datacurso'),
                ];
            }
        }

        return [
            'success' => false,
            'message' => get_string('usertokenlimit_reset_failed', 'aiprovider_datacurso'),
        ];
    }

    /**
     * Delete a role token limit record.
     *
     * @param int $id Record ID to delete.
     * @return array
     */
    public static function delete_role_token_limit(int $id): array {
        if ($id > 0) {
            $ok = role_token_limit_manager::delete($id);
            if ($ok) {
                return [
                    'success' => true,
                    'message' => get_string('usertokenlimit_deleted', 'aiprovider_datacurso'),
                ];
            }
        }

        return [
            'success' => false,
            'message' => get_string('usertokenlimit_delete_failed', 'aiprovider_datacurso'),
        ];
    }
}
