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
 * AMD module to handle the Webservice configuration actions for Datacurso.
 * It performs setup, token regeneration, and registration calls and
 * displays progress via UI notifications and a simple log list.
 *
 * @module      aiprovider_datacurso/webservice_config
 * @copyright   2025 Wilber Narvaez <wilber@buendata.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Ajax from 'core/ajax';
import Notification from 'core/notification';

const log = (msg, type = 'info') => {
    const list = document.getElementById('dc-ws-log');
    if (!list) {
        return;
    }
    const li = document.createElement('li');
    li.textContent = msg;
    li.classList.add('mb-1');
    if (type === 'success') {
        li.classList.add('text-success');
    } else if (type === 'error') {
        li.classList.add('text-danger');
    } else {
        li.classList.add('text-muted');
    }
    list.appendChild(li);
};

const call = (methodname) => {
    return Ajax.call([{
        methodname,
        args: {}
    }])[0];
};

const handleAction = async(action) => {
    try {
        let methodname;
        if (action === 'setup') {
            methodname = 'aiprovider_datacurso_webservice_setup';
            log('Starting setup…');
        } else if (action === 'regenerate') {
            methodname = 'aiprovider_datacurso_webservice_regenerate_token';
            log('Regenerating token…');
        } else if (action === 'retry') {
            methodname = 'aiprovider_datacurso_webservice_setup';
            log('Retrying setup…');
        } else {
            return;
        }

        const res = await call(methodname);
        if (res.messages && Array.isArray(res.messages)) {
            res.messages.forEach(m => log(m));
        }
        Notification.addNotification({
            message: 'Done: ' + action,
            type: 'success'
        });
    } catch (e) {
        Notification.exception(e);
        log('Error: ' + (e.message || e), 'error');
    }
};

export const init = () => {
    const container = document.querySelector('.aiprovider-datacurso-webservice');
    if (!container) {
        return;
    }
    container.addEventListener('click', (ev) => {
        const btn = ev.target.closest('button[data-action]');
        if (!btn) {
            return;
        }
        ev.preventDefault();
        const action = btn.getAttribute('data-action');
        handleAction(action);
    });
};

export default {init};
