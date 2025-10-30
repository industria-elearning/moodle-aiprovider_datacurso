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
 * Consumption history table management.
 *
 * @module     aiprovider_datacurso/consumption
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Ajax from 'core/ajax';
import { get_string as getString } from 'core/str';
import Templates from 'core/templates';
import Notification from 'core/notification';

export const init = async () => {

    // Elements DOM
    const filterService = document.getElementById('filter-service');
    const filterAction = document.getElementById('filter-action');
    const filterFrom = document.getElementById('filter-date-from');
    const filterTo = document.getElementById('filter-date-to');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    const pageInfo = document.getElementById('page-info');

    // Order
    let currentSortField = '';
    let currentSortDir = 'asc';

    // Select and input for limit and page
    const limitSelect = document.getElementById('filter-limit');
    const pageInput = document.getElementById('filter-page');

    // Init status
    let currentPage = parseInt(sessionStorage.getItem('consumptionPage')) || 1;
    let currentLimit = parseInt(sessionStorage.getItem('consumptionLimit')) || 10;

    const savePage = (page) => sessionStorage.setItem('consumptionPage', page);
    const saveLimit = (limit) => sessionStorage.setItem('consumptionLimit', limit);

    const loadServices = async () => {
        try {
            const response = await Ajax.call([{
                methodname: 'aiprovider_datacurso_get_services',
                args: {}
            }])[0];

            if (response?.services?.length) {
                response.services.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.name;
                    opt.textContent = s.name;
                    filterService.appendChild(opt);
                });
            }
        } catch (error) {
            Notification.exception(error);
            return [];
        }
    };

    const loadActions = async () => {
        try {
            const response = await Ajax.call([{
                methodname: 'aiprovider_datacurso_get_actions',
                args: {}
            }])[0];

            if (response?.actions?.length) {
                response.actions.forEach(a => {
                    const opt = document.createElement('option');
                    opt.value = a.id;
                    opt.textContent = a.name;
                    filterAction.appendChild(opt);
                });
            }
        } catch (error) {
            Notification.exception(error);
            return [];
        }
    };

    const renderTable = async (listconsumptions) => {
        const tableBody = document.getElementById('consumption-table-body');
        const context = { consumptions: listconsumptions };
        try {
            if (!listconsumptions || listconsumptions.length === 0) {
                const nodata = await Templates.render('aiprovider_datacurso/consumption_row', context);
                tableBody.innerHTML = nodata;
                return;
            }

            const html = await Templates.render('aiprovider_datacurso/consumption_row', context);

            tableBody.innerHTML = html;

        } catch (error) {
            Notification.exception(error);
            const nodata = await Templates.render('aiprovider_datacurso/consumption_row', context);
            tableBody.innerHTML = nodata;
        }
    };

    // Get history with filters
    const fetchData = () => {
        const serviceValue = filterService.value;
        const actionValue = filterAction.value;
        const fromValue = filterFrom.value;
        const toValue = filterTo.value;

        const args = {
            page: currentPage,
            limit: currentLimit,
            service: serviceValue !== 'all' ? serviceValue : '',
            action: actionValue !== 'all' ? actionValue : '',
            fromdate: fromValue || '',
            todate: toValue || ''
        };

        if (currentSortField) {
            args.shor = currentSortField;
            args.shordir = currentSortDir;
        }

        Ajax.call([{
            methodname: 'aiprovider_datacurso_get_consumption_history',
            args: args
        }])[0].then(async (response) => {

            const consumptions = response?.consumption || [];
            renderTable(consumptions);

            const pagination = response?.pagination;
            if (pagination) {
                const { current_page, total_pages, total } = pagination;
                const pageInfoText = await getString('pageinfo', 'aiprovider_datacurso', {
                    current: current_page,
                    totalpages: total_pages,
                    total: total
                });
                pageInfo.textContent = pageInfoText;
                if (pageInput) {
                    pageInput.value = current_page;
                }
                prevPageBtn.disabled = current_page <= 1;
                nextPageBtn.disabled = current_page >= total_pages;
            } else {
                pageInfo.textContent = '';
                prevPageBtn.disabled = true;
                nextPageBtn.disabled = true;
            }
        })
            .catch((error) => {
                Notification.exception(error);
                return [];
            });
    };

    // Event listeners for filters
    [filterService, filterAction, filterFrom, filterTo].forEach(el => {
        el.addEventListener('change', () => {
            currentPage = 1;
            savePage(currentPage);
            fetchData();
        });
    });

    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            savePage(currentPage);
            fetchData();
        }
    });

    nextPageBtn.addEventListener('click', () => {
        currentPage++;
        savePage(currentPage);
        fetchData();
    });

    if (limitSelect) {
        limitSelect.value = currentLimit;
        limitSelect.addEventListener('change', () => {
            currentLimit = parseInt(limitSelect.value);
            saveLimit(currentLimit);
            currentPage = 1;
            savePage(currentPage);
            fetchData();
        });
    }

    if (pageInput) {
        pageInput.addEventListener('change', () => {
            const newPage = parseInt(pageInput.value);
            if (newPage && newPage > 0) {
                currentPage = newPage;
                savePage(currentPage);
                fetchData();
            }
        });
    }

    // Sortable columns
    document.querySelectorAll('.sortable').forEach(header => {
        header.addEventListener('click', () => {
            const field = header.dataset.sort;
            const icon = header.querySelector('.sort-icon');

            if (currentSortField === field) {
                currentSortDir = currentSortDir === 'asc' ? 'desc' : 'asc';
            } else {
                currentSortField = field;
                currentSortDir = 'asc';
            }

            document.querySelectorAll('.sort-icon').forEach(i => {
                i.className = 'fa fa-sort sort-icon';
            });

            icon.className = currentSortDir === 'asc'
                ? 'fa fa-sort-up sort-icon'
                : 'fa fa-sort-down sort-icon';

            currentPage = 1;
            savePage(currentPage);
            fetchData();
        });
    });

    // Initial load
    Promise.all([loadServices(), loadActions()])
        .then(() => fetchData())
        .catch(Notification.exception);
};
