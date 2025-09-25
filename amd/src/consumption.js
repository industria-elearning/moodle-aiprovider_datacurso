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
 * TODO describe module consumption
 *
 * @module     aiprovider_datacurso/consumption
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/* eslint-disable */

import Ajax from 'core/ajax';
import { get_string as getString } from 'core/str';

export const init = () => {
    console.log("history consume")
    const tableBody = document.getElementById('consumption-table-body');

    Ajax.call([{
        methodname: 'aiprovider_datacurso_get_consumption_history',
        args: {}
    }])[0].then(async (response) => {
        tableBody.innerHTML = '';

        console.log("respuesta", response)
        // Validamos la estructura
        const consumos = response?.consumos || [];

        console.log("consumo", consumos)

        if (consumos.length === 0) {
            const nodata = await getString('nodata', 'aiprovider_datacurso');
            tableBody.innerHTML = `<tr><td colspan="6">${nodata}</td></tr>`;
            return;
        }

        consumos.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
            <td>${item.id_consumo}</td>
            <td>${item.id_usuario}</td>
            <td>${item.accion}</td>
            <td>${item.cantidad_tokens}</td>
            <td>${item.saldo_restante}</td>
            <td>${item.fecha}</td>
        `;
            tableBody.appendChild(row);
        });
    }).catch(async (error) => {
        const nodata = await getString('nodata', 'aiprovider_datacurso');
        tableBody.innerHTML = `<tr><td colspan="6">${nodata}</td></tr>`;
    });

};