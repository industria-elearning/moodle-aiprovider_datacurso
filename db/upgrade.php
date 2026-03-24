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

/**
 * Plugin upgrade steps are defined here.
 *
 * @package     aiprovider_datacurso
 * @category    upgrade
 * @copyright   Josue <josue@datacurso.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Execute aiprovider_datacurso upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_aiprovider_datacurso_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    // For further information please read {@link https://docs.moodle.org/dev/Upgrade_API}.
    //
    // You will also have to create the db/install.xml file by using the XMLDB Editor.
    // Documentation for the XMLDB Editor can be found at {@link https://docs.moodle.org/dev/XMLDB_editor}.
    if ($oldversion < 2025110500) {
        // Define table aiprovider_datacurso_rlimit to be created.
        $table = new xmldb_table('aiprovider_datacurso_rlimit');

        // Adding fields to table aiprovider_datacurso_rlimit.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('serviceid', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('windowstart', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('tokensused', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('lastsync', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table aiprovider_datacurso_rlimit.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('usermodified', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);
        $table->add_key('userid', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Conditionally launch create table for aiprovider_datacurso_rlimit.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Datacurso savepoint reached.
        upgrade_plugin_savepoint(true, 2025110500, 'aiprovider', 'datacurso');
    }

    if ($oldversion < 2025110600) {
        try {
            \aiprovider_datacurso\webservice_config::upgrade_sync_ws_and_capabilities();
        } catch (\Exception $e) {
            \core\notification::add(
                get_string('upgrade_sync_error', 'aiprovider_datacurso', $e->getMessage()),
                \core\output\notification::NOTIFY_ERROR
            );
        }
        upgrade_plugin_savepoint(true, 2025110600, 'aiprovider', 'datacurso');
    }

    if ($oldversion < 2025112705) {
        try {
            \aiprovider_datacurso\webservice_config::upgrade_sync_ws_and_capabilities();
        } catch (\Exception $e) {
            \core\notification::add(
                get_string('upgrade_sync_error', 'aiprovider_datacurso', $e->getMessage()),
                \core\output\notification::NOTIFY_ERROR
            );
        }
        upgrade_plugin_savepoint(true, 2025112705, 'aiprovider', 'datacurso');
    }

    if ($oldversion < 2025120201) {
        try {
            \aiprovider_datacurso\webservice_config::upgrade_sync_ws_and_capabilities();
        } catch (\Exception $e) {
            \core\notification::add(
                get_string('upgrade_sync_error', 'aiprovider_datacurso', $e->getMessage()),
                \core\output\notification::NOTIFY_ERROR
            );
        }
        upgrade_plugin_savepoint(true, 2025120201, 'aiprovider', 'datacurso');
    }

    if ($oldversion < 2025120301) {
        // Define table aiprovider_datacurso_userlimit to be created.
        $table = new xmldb_table('aiprovider_datacurso_userlimit');

        // Adding fields to table aiprovider_datacurso_userlimit.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('tokenlimit', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('tokensused', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('countfrom', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('lastsync', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table aiprovider_datacurso_userlimit.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('userid_fk', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);
        $table->add_key('usermodified_fk', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        // Conditionally launch create table for aiprovider_datacurso_userlimit.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Datacurso savepoint reached.
        upgrade_plugin_savepoint(true, 2025120301, 'aiprovider', 'datacurso');
    }

    if ($oldversion < 2026030200) {
        $table = new xmldb_table('aiprovider_datacurso_userlimit');

        $field = new xmldb_field('nextresetat', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'lastsync');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field('recurringintervalenabled', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0', 'lastsync');
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field(
            'recurringintervalunit',
            XMLDB_TYPE_CHAR,
            '10',
            null,
            XMLDB_NOTNULL,
            null,
            'day',
            'recurringintervalenabled'
        );
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        $field = new xmldb_field(
            'recurringintervalvalue',
            XMLDB_TYPE_INTEGER,
            '10',
            null,
            XMLDB_NOTNULL,
            null,
            '0',
            'recurringintervalunit'
        );
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        upgrade_plugin_savepoint(true, 2026030200, 'aiprovider', 'datacurso');
    }

    if ($oldversion < 2026031704) {
        $table = new xmldb_table('aiprovider_datacurso_rolelimit');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('roleid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('priority', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, 'medium');
        $table->add_field('tokenlimit', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('tokensused', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('countfrom', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('lastsync', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('recurringintervalenabled', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('recurringintervalunit', XMLDB_TYPE_CHAR, '10', null, XMLDB_NOTNULL, null, 'day');
        $table->add_field('recurringintervalvalue', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('nextresetat', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('usermodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('roleid_fk', XMLDB_KEY_FOREIGN, ['roleid'], 'role', ['id']);
        $table->add_key('roleid_uq', XMLDB_KEY_UNIQUE, ['roleid']);
        $table->add_key('usermodified_fk', XMLDB_KEY_FOREIGN, ['usermodified'], 'user', ['id']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        $table = new xmldb_table('aiprovider_datacurso_rolelimit_userusage');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('rolelimitid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('tokensused', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('lastsync', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('rolelimitid_fk', XMLDB_KEY_FOREIGN, ['rolelimitid'], 'aiprovider_datacurso_rolelimit', ['id']);
        $table->add_key('userid_fk', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);
        $table->add_key('rolelimituserid_uq', XMLDB_KEY_UNIQUE, ['rolelimitid', 'userid']);

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_plugin_savepoint(true, 2026031704, 'aiprovider', 'datacurso');
    }

    return true;
}
