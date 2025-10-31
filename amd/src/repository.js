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

/**
 * TODO describe module repository
 *
 * @module     aiprovider_datacurso/repository
 * @copyright  2025 Wilber Narvaez <wilber@buendata.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import Ajax from 'core/ajax';

/**
 * Setup the webservice for Datacurso.
 */
export function webserviceSetup() {
    return Ajax.call([{
        methodname: 'aiprovider_datacurso_webservice_setup',
        args: {}
    }])[0];
}

/**
 * Regenerate the webservice token for Datacurso.
 */
export function webserviceRegenerateToken() {
    return Ajax.call([{
        methodname: 'aiprovider_datacurso_webservice_regenerate_token',
        args: {}
    }])[0];
}

/**
 * Get current Datacurso webservice status.
 */
export function webserviceGetStatus() {
    return Ajax.call([{
        methodname: 'aiprovider_datacurso_webservice_get_status',
        args: {}
    }])[0];
}

/**
 * Get consumption history for Datacurso.
 * @param {Object} args - The arguments for the consumption history.
 */
export function getConsumptionHistory(args) {
    return Ajax.call([{
        methodname: 'aiprovider_datacurso_get_consumption_history',
        args: args
    }])[0];
}