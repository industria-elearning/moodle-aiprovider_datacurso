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
 * Role token limits actions.
 *
 * @module     aiprovider_datacurso/role_token_limits
 * @copyright  2026 Datacurso
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Notification from 'core/notification';
import { get_strings as getStrings, get_string as getString } from 'core/str';
import { createRoleTokenLimitModal } from 'aiprovider_datacurso/modals';
import { deleteRoleTokenLimit, resetRoleTokenUsage } from 'aiprovider_datacurso/repository';

/**
 * Confirm helper.
 *
 * @param {string} title
 * @param {string} message
 * @param {Function} onYes
 */
const confirmAction = async (title, message, onYes) => {
    const [yes, no] = await getStrings([
        { key: 'yes' },
        { key: 'no' }
    ]);
    Notification.confirm(title, message, yes, no, onYes);
};

/**
 * Handle delete click.
 *
 * @param {HTMLElement} link
 */
const handleDelete = async (link) => {
    const id = link.dataset.id;
    const rolename = link.dataset.rolename || '';
    const [title, message] = await getStrings([
        { key: 'confirm_delete_title', component: 'aiprovider_datacurso' },
        { key: 'confirm_delete_message', component: 'aiprovider_datacurso', param: rolename }
    ]);

    await confirmAction(title, message, async () => {
        try {
            const result = await deleteRoleTokenLimit(id);
            if (result && result.success) {
                window.location.reload();
                return;
            }
            const err = await getString('usertokenlimit_delete_failed', 'aiprovider_datacurso');
            Notification.alert(title, result && result.message ? result.message : err);
        } catch (ex) {
            const err = await getString('usertokenlimit_delete_failed', 'aiprovider_datacurso');
            Notification.alert(title, err);
        }
    });
};

/**
 * Handle reset click.
 *
 * @param {HTMLElement} link
 */
const handleReset = async (link) => {
    const id = link.dataset.id;
    const rolename = link.dataset.rolename || '';
    const [title, message] = await getStrings([
        { key: 'usertokenlimit_reset_usage', component: 'aiprovider_datacurso' },
        { key: 'confirm_reset_usage_message', component: 'aiprovider_datacurso', param: rolename }
    ]);

    await confirmAction(title, message, async () => {
        try {
            const result = await resetRoleTokenUsage(id);
            if (result && result.success) {
                window.location.reload();
                return;
            }
            const err = await getString('usertokenlimit_reset_failed', 'aiprovider_datacurso');
            Notification.alert(title, result && result.message ? result.message : err);
        } catch (ex) {
            const err = await getString('usertokenlimit_reset_failed', 'aiprovider_datacurso');
            Notification.alert(title, err);
        }
    });
};

/**
 * Init handlers.
 */
export const init = () => {
    const root = document.querySelector('[data-region="aiprovider-datacurso-rolelimits"]');
    if (!root) {
        return;
    }

    root.addEventListener('click', async (e) => {
        const deleteLink = e.target.closest('[data-action="delete"]');
        if (deleteLink) {
            e.preventDefault();
            await handleDelete(deleteLink);
            return;
        }

        const resetLink = e.target.closest('[data-action="resetusage"]');
        if (resetLink) {
            e.preventDefault();
            await handleReset(resetLink);
            return;
        }

        const addLink = e.target.closest('[data-action="openadd"]');
        if (addLink) {
            e.preventDefault();
            const returnurl = window.location.pathname + window.location.search;
            const modal = createRoleTokenLimitModal(
                addLink,
                getString('roletokenlimit_add_title', 'aiprovider_datacurso'),
                0,
                returnurl
            );
            modal.addEventListener(modal.events.FORM_SUBMITTED, (event) => {
                if (event && event.detail) {
                    window.location.href = event.detail;
                }
            });
            modal.show();
            return;
        }

        const editLink = e.target.closest('[data-action="openedit"]');
        if (editLink) {
            e.preventDefault();
            const id = parseInt(editLink.dataset.id, 10) || 0;
            const rolename = editLink.dataset.rolename || '';
            const returnurl = window.location.pathname + window.location.search;
            const modal = createRoleTokenLimitModal(
                editLink,
                getString('roletokenlimit_edit_title', 'aiprovider_datacurso', rolename),
                id,
                returnurl,
                rolename
            );
            modal.addEventListener(modal.events.FORM_SUBMITTED, (event) => {
                if (event && event.detail) {
                    window.location.href = event.detail;
                }
            });
            modal.show();
        }
    });
};
