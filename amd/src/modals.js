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
 * TODO describe module modals
 *
 * @module     aiprovider_datacurso/modals
 * @copyright  2025 Wilber Narvaez <https://datacurso.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import ModalForm from 'core_form/modalform';
import { getString } from 'core/str';

/**
 * Return modal instance
 *
 * @param {EventTarget} triggerElement
 * @param {Promise|string} modalTitle
 * @param {String} formClass
 * @param {Object} formArgs
 * @return {ModalForm}
 */
const createModalForm = (triggerElement, modalTitle, formClass, formArgs) => {
    return new ModalForm({
        modalConfig: {
            title: modalTitle,
        },
        formClass: formClass,
        args: formArgs,
        saveButtonText: getString('savechanges', 'moodle'),
        returnFocus: triggerElement,
    });
};

/**
 * Return Datacurso user token limit modal instance
 *
 * @param {EventTarget} triggerElement
 * @param {Promise|string} modalTitle
 * @param {Number} id
 * @param {String} returnurl
 * @param {String} userlabel
 * @return {ModalForm}
 */
export const createUserTokenLimitModal = (triggerElement, modalTitle, id = 0, returnurl = '', userlabel = '') => {
    return createModalForm(
        triggerElement,
        modalTitle,
        'aiprovider_datacurso\\form\\user_token_limit_form',
        { id, returnurl, userlabel }
    );
};

/**
 * Return Datacurso role token limit modal instance.
 *
 * @param {EventTarget} triggerElement
 * @param {Promise|string} modalTitle
 * @param {Number} id
 * @param {String} returnurl
 * @param {String} rolelabel
 * @return {ModalForm}
 */
export const createRoleTokenLimitModal = (triggerElement, modalTitle, id = 0, returnurl = '', rolelabel = '') => {
    return createModalForm(
        triggerElement,
        modalTitle,
        'aiprovider_datacurso\\form\\role_token_limit_form',
        { id, returnurl, rolelabel }
    );
};
