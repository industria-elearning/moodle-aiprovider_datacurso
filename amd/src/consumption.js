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
    const filterService = document.getElementById('filter-service');
    const filterAction = document.getElementById('filter-action');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    const pageInfo = document.getElementById('page-info');

    let allConsumos = [];
    let filteredConsumos = [];
    let currentPage = 1;
    const rowsPerPage = 10;

    // Render the rows in the table
    const renderTable = () => {
        tableBody.innerHTML = '';
        if (filteredConsumos.length === 0) {
            getString('nodata', 'aiprovider_datacurso').then(nodata => {
                tableBody.innerHTML = `<tr><td colspan="7">${nodata}</td></tr>`;
            });
            pageInfo.textContent = '';
            prevPageBtn.disabled = true;
            nextPageBtn.disabled = true;
            return;
        }

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageItems = filteredConsumos.slice(start, end);

        pageItems.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.id_consumo}</td>
                <td>${item.id_usuario}</td>
                <td>${item.accion}</td>
                <td>${item.servicio}</td>
                <td>${item.cantidad_tokens}</td>
                <td>${item.saldo_restante}</td>
                <td>${item.fecha}</td>
            `;
            tableBody.appendChild(row);
        });

        const totalPages = Math.ceil(filteredConsumos.length / rowsPerPage);
        pageInfo.textContent = `Página ${currentPage} de ${totalPages}`;
        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages;
    };

    const applyFilters = () => {
        const serviceValue = filterService.value;
        const actionValue = filterAction.value;

        filteredConsumos = allConsumos.filter(item => {
            const serviceMatch = serviceValue === 'all' || item.servicio === serviceValue;
            const actionMatch = actionValue === 'all' || item.accion === actionValue;
            return serviceMatch && actionMatch;
        });

        currentPage = 1;
        renderTable();
    };

    Ajax.call([{
        methodname: 'aiprovider_datacurso_get_consumption_history',
        args: {}
    }])[0].then(async (response) => {
        allConsumos = response?.consumos || [];

        const uniqueServices = [...new Set(allConsumos.map(c => c.servicio))];
        const uniqueActions = [...new Set(allConsumos.map(c => c.accion))];

        uniqueServices.forEach(s => {
            const opt = document.createElement('option');
            opt.value = s;
            opt.textContent = s;
            filterService.appendChild(opt);
        });

        uniqueActions.forEach(a => {
            const opt = document.createElement('option');
            opt.value = a;
            opt.textContent = a;
            filterAction.appendChild(opt);
        });

        // Listeners filters
        filterService.addEventListener('change', applyFilters);
        filterAction.addEventListener('change', applyFilters);

        // Listeners pagination
        prevPageBtn.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        });
        nextPageBtn.addEventListener('click', () => {
            const totalPages = Math.ceil(filteredConsumos.length / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        });

        // Render initial
        filteredConsumos = allConsumos;
        renderTable();
    }).catch(async (error) => {
        const nodata = await getString('nodata', 'aiprovider_datacurso');
        tableBody.innerHTML = `<tr><td colspan="7">${nodata}</td></tr>`;
    });
};
