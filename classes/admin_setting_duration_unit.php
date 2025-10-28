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

use admin_setting;

/**
 * Composite setting: numeric duration value + unit selector (hours, days, weeks, months, years).
 *
 * Stores a JSON string: {"value": <int>, "unit": "hours|days|weeks|months|years"}
 *
 * @package    aiprovider_datacurso
 * @copyright  2025
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class admin_setting_duration_unit extends admin_setting {
    /** @var array Allowed units and their language string ids. */
    protected array $units = [
        'hours' => 'hours',
        'days' => 'days',
        'weeks' => 'weeks',
        'months' => 'months',
        'years' => 'years',
    ];

    /**
     * Get the current stored setting value.
     */
    public function get_setting() {
        return $this->config_read($this->name);
    }

    /**
     * Validate the composite value.
     * @param mixed $data
     * @return true|string True if ok, or error string
     */
    public function validate($data) {
        $value = 0;
        $unit = '';

        if (is_array($data)) {
            $value = (int)($data['value'] ?? 0);
            $unit = (string)($data['unit'] ?? '');
        } else if (is_string($data) && $data !== '') {
            $decoded = json_decode($data, true);
            if (is_array($decoded)) {
                $value = (int)($decoded['value'] ?? 0);
                $unit = (string)($decoded['unit'] ?? '');
            }
        }

        if ($unit !== '' && !array_key_exists($unit, $this->units)) {
            return get_string('validateerror', 'admin');
        }

        if ($unit === '' && $value === 0) {
            // Empty is allowed.
            return true;
        }

        if ($value <= 0) {
            return get_string('validateerror', 'admin');
        }

        return true;
    }

    /**
     * Write the composite value to config as JSON.
     * @param mixed $data
     * @return string '' on success, or error string
     */
    public function write_setting($data) {
        $validated = $this->validate($data);
        if ($validated !== true) {
            return $validated;
        }

        if (is_array($data)) {
            $value = (int)($data['value'] ?? 0);
            $unit = (string)($data['unit'] ?? '');

            if ($unit === '' && $value === 0) {
                return $this->config_write($this->name, '') ? '' : get_string('errorsetting', 'admin');
            }

            $tostore = json_encode(['value' => $value, 'unit' => $unit]);
            return $this->config_write($this->name, $tostore) ? '' : get_string('errorsetting', 'admin');
        }

        if ($data === '' || $data === null) {
            return $this->config_write($this->name, '') ? '' : get_string('errorsetting', 'admin');
        }

        $decoded = json_decode((string)$data, true);
        if (is_array($decoded)) {
            $decoded['value'] = (int)($decoded['value'] ?? 0);
            $decoded['unit'] = (string)($decoded['unit'] ?? '');
            $tostore = json_encode($decoded);
        } else {
            $tostore = (string)$data;
        }
        return $this->config_write($this->name, $tostore) ? '' : get_string('errorsetting', 'admin');
    }

    /**
     * Render numeric input + select using core templates.
     * @param mixed $data
     * @param string $query
     */
    public function output_html($data, $query = '') {
        global $OUTPUT;

        $value = '';
        $unit = '';
        if (is_array($data)) {
            $value = (string)($data['value'] ?? '');
            $unit = (string)($data['unit'] ?? '');
        } else if (is_string($data) && $data !== '') {
            $decoded = json_decode($data, true);
            if (is_array($decoded)) {
                $value = (string)($decoded['value'] ?? '');
                $unit = (string)($decoded['unit'] ?? '');
            }
        }

        // Text input for numeric value via core_admin/setting_configtext.
        $textcontext = (object)[
            'size' => 6,
            'id' => $this->get_id() . '_value',
            'name' => $this->get_full_name() . '[value]',
            'value' => $value,
            'forceltr' => true,
            'readonly' => $this->is_readonly(),
            'data' => [],
            'maxcharacter' => false,
        ];
        $texthtml = $OUTPUT->render_from_template('core_admin/setting_configtext', $textcontext);

        // Select for unit via core_admin/setting_configselect.
        $selectid = $this->get_id() . '_unit';
        $selectname = $this->get_full_name() . '[unit]';
        $options = [];
        foreach ($this->units as $val => $strid) {
            $options[] = [
                'value' => $val,
                'name' => get_string($strid, 'aiprovider_datacurso'),
                'selected' => ($val === $unit),
            ];
        }
        $selectcontext = (object)[
            'id' => $selectid,
            'name' => $selectname,
            'options' => $options,
            'readonly' => $this->is_readonly(),
        ];
        $selecthtml = $OUTPUT->render_from_template('core_admin/setting_configselect', $selectcontext);

        $element = $texthtml . ' ' . $selecthtml;

        // Make default human-readable (e.g., "1 hour") instead of raw JSON.
        $defaultraw = $this->get_defaultsetting();
        $defaultlabel = $this->format_default_label($defaultraw);

        return format_admin_setting(
            $this,
            $this->visiblename,
            $element,
            $this->description,
            true,
            '',
            $defaultlabel,
            $query
        );
    }

    /**
     * Format the default value to a readable label, e.g., "1 hour", "2 weeks".
     * Accepts array or JSON string or empty.
     *
     * @param mixed $default
     * @return string
     */
    private function format_default_label($default): string {
        $value = 0;
        $unit = '';
        if (is_array($default)) {
            $value = (int)($default['value'] ?? 0);
            $unit = (string)($default['unit'] ?? '');
        } else if (is_string($default) && $default !== '') {
            $decoded = json_decode($default, true);
            if (is_array($decoded)) {
                $value = (int)($decoded['value'] ?? 0);
                $unit = (string)($decoded['unit'] ?? '');
            }
        }
        if ($value <= 0 || $unit === '' || !array_key_exists($unit, $this->units)) {
            return '';
        }
        // Use language string for unit with basic singular/plural.
        $unitkey = $value === 1 ? rtrim($unit, 's') : $unit; // expects 'hour'/'hours', etc.
        $unitstr = get_string($unitkey, 'aiprovider_datacurso');
        return trim($value . ' ' . $unitstr);
    }
}
