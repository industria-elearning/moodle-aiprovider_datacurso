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

namespace aiprovider_datacurso\form;

defined('MOODLE_INTERNAL') || die();

use aiprovider_datacurso\local\role_token_limit_manager;
use core\context\system as context_system;
use core_form\dynamic_form;

require_once($CFG->libdir . '/formslib.php');

/**
 * Form to create or edit per-role token limits.
 *
 * @package    aiprovider_datacurso
 * @copyright  2026 Datacurso
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class role_token_limit_form extends dynamic_form {
    /**
     * Define the form fields.
     */
    public function definition(): void {
        $mform = $this->_form;

        $id = (int)$this->optional_param('id', 0, PARAM_INT);
        $returnurl = (string)$this->optional_param('returnurl', '', PARAM_LOCALURL);
        $editing = $id > 0;
        $priorityoptions = [
            'high' => get_string('roletokenlimit_priority_high', 'aiprovider_datacurso'),
            'medium' => get_string('roletokenlimit_priority_medium', 'aiprovider_datacurso'),
            'low' => get_string('roletokenlimit_priority_low', 'aiprovider_datacurso'),
        ];
        $pool = role_token_limit_manager::get_license_pool($editing ? $id : null);

        if ($editing) {
            $rolelabel = (string)$this->optional_param('rolelabel', '', PARAM_TEXT);
            $mform->addElement(
                'static',
                'rolelabel',
                get_string('roletokenlimit_role', 'aiprovider_datacurso'),
                format_string($rolelabel)
            );
            $mform->addElement('hidden', 'roleid');
            $mform->setType('roleid', PARAM_INT);
        } else {
            $mform->addElement(
                'select',
                'roleid',
                get_string('roletokenlimit_role', 'aiprovider_datacurso'),
                self::get_role_options()
            );
            $mform->addRule('roleid', get_string('required'), 'required', null, 'client');
            $mform->setType('roleid', PARAM_INT);
        }

        $mform->addElement('text', 'tokenlimit', get_string('usertokenlimit_limit', 'aiprovider_datacurso'));
        $mform->addHelpButton('tokenlimit', 'usertokenlimit_limit', 'aiprovider_datacurso');
        $mform->setType('tokenlimit', PARAM_INT);
        $mform->addRule('tokenlimit', get_string('required'), 'required', null, 'client');
        $mform->addRule('tokenlimit', null, 'numeric', null, 'client');

        $mform->addElement('select', 'priority', get_string('roletokenlimit_priority', 'aiprovider_datacurso'), $priorityoptions);
        $mform->addHelpButton('priority', 'roletokenlimit_priority', 'aiprovider_datacurso');
        $mform->setType('priority', PARAM_ALPHA);
        $mform->addRule('priority', get_string('required'), 'required', null, 'client');
        $mform->setDefault('priority', 'medium');

        $mform->addElement(
            'advcheckbox',
            'recurringintervalenabled',
            get_string('usertokenlimit_recurringenabled', 'aiprovider_datacurso')
        );
        $mform->addHelpButton('recurringintervalenabled', 'usertokenlimit_recurringenabled', 'aiprovider_datacurso');
        $mform->setType('recurringintervalenabled', PARAM_INT);
        $mform->setDefault('recurringintervalenabled', 0);

        $unitoptions = [
            'hour' => get_string('usertokenlimit_recurringunit_hour', 'aiprovider_datacurso'),
            'day' => get_string('usertokenlimit_recurringunit_day', 'aiprovider_datacurso'),
            'week' => get_string('usertokenlimit_recurringunit_week', 'aiprovider_datacurso'),
            'month' => get_string('usertokenlimit_recurringunit_month', 'aiprovider_datacurso'),
            'year' => get_string('usertokenlimit_recurringunit_year', 'aiprovider_datacurso'),
        ];
        $mform->addElement(
            'select',
            'recurringintervalunit',
            get_string('usertokenlimit_recurringunit', 'aiprovider_datacurso'),
            $unitoptions
        );
        $mform->addHelpButton('recurringintervalunit', 'usertokenlimit_recurringunit', 'aiprovider_datacurso');
        $mform->setType('recurringintervalunit', PARAM_ALPHA);
        $mform->setDefault('recurringintervalunit', 'day');
        $mform->hideIf('recurringintervalunit', 'recurringintervalenabled', 'notchecked');

        $mform->addElement('text', 'recurringintervalvalue', get_string('usertokenlimit_recurringvalue', 'aiprovider_datacurso'));
        $mform->addHelpButton('recurringintervalvalue', 'usertokenlimit_recurringvalue', 'aiprovider_datacurso');
        $mform->setType('recurringintervalvalue', PARAM_INT);
        $mform->addRule('recurringintervalvalue', null, 'numeric', null, 'client');
        $mform->setDefault('recurringintervalvalue', 1);
        $mform->hideIf('recurringintervalvalue', 'recurringintervalenabled', 'notchecked');

        if (($pool['status'] ?? 'error') === 'success') {
            $mform->addElement(
                'static',
                'licensebalance',
                get_string('usertokenlimit_licensebalance', 'aiprovider_datacurso'),
                (string)((int)$pool['licensebalance'])
            );
            $mform->addElement(
                'static',
                'availabletoassign',
                get_string('usertokenlimit_availabletoassign', 'aiprovider_datacurso'),
                (string)((int)$pool['availabletoassign'])
            );
        } else {
            $mform->addElement(
                'static',
                'licensebalanceerror',
                get_string('usertokenlimit_licensebalance', 'aiprovider_datacurso'),
                get_string('errorgetbalancecredits', 'aiprovider_datacurso')
            );
        }

        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'returnurl', $returnurl);
        $mform->setType('returnurl', PARAM_LOCALURL);
    }

    /**
     * Check access to submit dynamically.
     */
    protected function check_access_for_dynamic_submission(): void {
        $context = context_system::instance();
        require_capability('aiprovider/datacurso:managetokenlimits', $context);
    }

    /**
     * Context for dynamic submission.
     *
     * @return \context
     */
    public function get_context_for_dynamic_submission(): \context {
        return context_system::instance();
    }

    /**
     * Page URL for dynamic submission.
     *
     * @return \moodle_url
     */
    public function get_page_url_for_dynamic_submission(): \moodle_url {
        $returnurl = (string)$this->optional_param('returnurl', '', PARAM_LOCALURL);
        if (!empty($returnurl)) {
            return new \moodle_url($returnurl);
        }
        return new \moodle_url('/ai/provider/datacurso/admin/role_token_limits.php');
    }

    /**
     * Preload data when editing.
     */
    public function set_data_for_dynamic_submission(): void {
        global $DB;

        $id = (int)$this->optional_param('id', 0, PARAM_INT);
        if ($id) {
            $record = role_token_limit_manager::get_by_id($id);
            if ($record) {
                $role = $DB->get_record('role', ['id' => (int)$record->roleid], '*', MUST_EXIST);
                $data = new \stdClass();
                $data->id = (int)$record->id;
                $data->roleid = (int)$record->roleid;
                $data->tokenlimit = (int)$record->tokenlimit;
                $data->priority = role_token_limit_manager::priority_to_level((string)$record->priority);
                $data->recurringintervalenabled = (int)($record->recurringintervalenabled ?? 0);
                $data->recurringintervalunit = (string)($record->recurringintervalunit ?? 'day');
                $data->recurringintervalvalue = (int)($record->recurringintervalvalue ?? 0);
                $data->rolelabel = role_get_name($role, context_system::instance());
                $this->set_data($data);
            }
        }
    }

    /**
     * Process submission and return redirect URL.
     *
     * @return string
     */
    public function process_dynamic_submission() {
        $data = $this->get_data();
        role_token_limit_manager::save(
            (int)$data->roleid,
            (int)$data->tokenlimit,
            (string)$data->priority,
            (int)$data->id,
            (int)($data->recurringintervalenabled ?? 0),
            (string)($data->recurringintervalunit ?? 'day'),
            (int)($data->recurringintervalvalue ?? 0)
        );

        $returnurl = !empty($data->returnurl)
            ? new \moodle_url($data->returnurl)
            : new \moodle_url('/ai/provider/datacurso/admin/role_token_limits.php');
        return $returnurl->out(false);
    }

    /**
     * Form validation.
     *
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files): array {
        $errors = parent::validation($data, $files);

        $roleid = (int)($data['roleid'] ?? 0);
        if (empty($data['id']) && $roleid <= 0) {
            $errors['roleid'] = get_string('required');
        }

        $tokenlimit = (int)($data['tokenlimit'] ?? 0);
        if ($tokenlimit < 0) {
            $errors['tokenlimit'] = get_string('usertokenlimit_limit_invalid_nonnegative', 'aiprovider_datacurso');
        }

        $priority = (string)($data['priority'] ?? '');
        if (!in_array($priority, ['high', 'medium', 'low'], true)) {
            $errors['priority'] = get_string('roletokenlimit_priority_invalid', 'aiprovider_datacurso');
        }

        $recurringenabled = !empty($data['recurringintervalenabled']);
        $recurringintervalvalue = (int)($data['recurringintervalvalue'] ?? 0);
        if ($recurringenabled && $recurringintervalvalue <= 0) {
            $errors['recurringintervalvalue'] = get_string(
                'usertokenlimit_recurringvalue_invalid_positive',
                'aiprovider_datacurso'
            );
        }

        $allowedunits = ['hour', 'day', 'week', 'month', 'year'];
        $recurringintervalunit = (string)($data['recurringintervalunit'] ?? 'day');
        if ($recurringenabled && !in_array($recurringintervalunit, $allowedunits, true)) {
            $errors['recurringintervalunit'] = get_string('usertokenlimit_recurringunit_invalid', 'aiprovider_datacurso');
        }

        $id = (int)($data['id'] ?? 0);
        $pool = role_token_limit_manager::get_license_pool($id > 0 ? $id : null);
        if (($pool['status'] ?? 'error') !== 'success') {
            $errors['tokenlimit'] = get_string('errorgetbalancecredits', 'aiprovider_datacurso');
        } else if ($tokenlimit > (int)$pool['availabletoassign']) {
            $params = (object)[
                'requested' => $tokenlimit,
                'available' => (int)$pool['availabletoassign'],
            ];
            $errors['tokenlimit'] = get_string('error_usertokenlimit_available_exceeded', 'aiprovider_datacurso', $params);
        }

        return $errors;
    }

    /**
     * Build selectable role list.
     *
     * @return array
     */
    private static function get_role_options(): array {
        $roles = role_fix_names(get_all_roles());
        $options = ['' => get_string('choose')];
        foreach ($roles as $role) {
            $options[(int)$role->id] = role_get_name($role, context_system::instance());
        }
        return $options;
    }
}
