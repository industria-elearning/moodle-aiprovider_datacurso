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

/**
 * Upgrade tests for Datacurso AI provider.
 *
 * @package    aiprovider_datacurso
 * @category   test
 * @copyright  2026 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class upgrade_test extends \advanced_testcase {
    /**
     * Ensure upgrade removes the legacy ratelimit table.
     *
     * @covers \xmldb_aiprovider_datacurso_upgrade
     */
    public function test_upgrade_removes_legacy_ratelimit_table(): void {
        global $CFG, $DB;

        $this->resetAfterTest(true);

        require_once($CFG->dirroot . '/ai/provider/datacurso/db/upgrade.php');

        $dbman = $DB->get_manager();
        $legacytable = new \xmldb_table('aiprovider_datacurso_rl');
        if ($dbman->table_exists($legacytable)) {
            $dbman->drop_table($legacytable);
        }

        $this->create_legacy_ratelimit_table($legacytable);
        $this->assertTrue($dbman->table_exists($legacytable));

        \xmldb_aiprovider_datacurso_upgrade(2026042900);

        $this->assertFalse($dbman->table_exists($legacytable));
    }

    /**
     * Create legacy ratelimit table used before rename.
     *
     * @param \xmldb_table $table
     * @return void
     */
    private function create_legacy_ratelimit_table(\xmldb_table $table): void {
        global $DB;

        $dbman = $DB->get_manager();

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('serviceid', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, '');
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        $dbman->create_table($table);
    }
}
