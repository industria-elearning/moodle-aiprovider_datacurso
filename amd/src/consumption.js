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

/* eslint-disable */
import Ajax from 'core/ajax';
import { get_string as getString } from 'core/str';

export const init = () => {

    // 🔒 Evita doble inicialización del módulo
    if (window._consumptionInitialized) {
        console.warn("⚠️ Módulo de consumo ya inicializado — se omite nueva carga.");
        return;
    }
    window._consumptionInitialized = true;

    console.log("Historial de consumo inicializado ✅");

    // Elementos del DOM
    const tableBody = document.getElementById('consumption-table-body');
    const filterService = document.getElementById('filter-service');
    const filterAction = document.getElementById('filter-action');
    const filterFrom = document.getElementById('filter-date-from');
    const filterTo = document.getElementById('filter-date-to');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    const pageInfo = document.getElementById('page-info');

    // 📄 Estado inicial
    let currentPage = parseInt(sessionStorage.getItem('consumptionPage')) || 1;

    const savePage = (page) => {
        sessionStorage.setItem('consumptionPage', page);
    };

    /**
     * 🧩 Cargar lista de servicios desde el WS
     */
    const loadServices = async () => {
        try {
            const response = await Ajax.call([{
                methodname: 'aiprovider_datacurso_get_services',
                args: {}
            }])[0];

            // Limpiar y agregar opción "todos"
            filterService.innerHTML = '<option value="all">Todos los servicios</option>';

            if (response?.services?.length) {
                response.services.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.id;
                    opt.textContent = s.name;
                    filterService.appendChild(opt);
                });
            } else {
                console.warn("⚠️ No se encontraron servicios para mostrar.");
            }
        } catch (error) {
            console.error("❌ Error al cargar servicios:", error);
        }
    };

    /**
     * 🧩 Cargar lista de acciones desde el WS
     */
    const loadActions = async () => {
        try {
            const response = await Ajax.call([{
                methodname: 'aiprovider_datacurso_get_actions',
                args: {}
            }])[0];

            // Limpiar y agregar opción "todas"
            filterAction.innerHTML = '<option value="all">Todas las acciones</option>';

            if (response?.actions?.length) {
                response.actions.forEach(a => {
                    const opt = document.createElement('option');
                    opt.value = a.id;
                    opt.textContent = a.name;
                    filterAction.appendChild(opt);
                });
            } else {
                console.warn("⚠️ No se encontraron acciones para mostrar.");
            }
        } catch (error) {
            console.error("❌ Error al cargar acciones:", error);
        }
    };

    /**
     * 🧾 Renderizar tabla de consumos
     */
    const renderTable = (consumos) => {
        tableBody.innerHTML = '';
        if (!consumos || consumos.length === 0) {
            getString('nodata', 'aiprovider_datacurso').then(nodata => {
                tableBody.innerHTML = `<tr><td colspan="7">${nodata}</td></tr>`;
            });
            pageInfo.textContent = '';
            prevPageBtn.disabled = true;
            nextPageBtn.disabled = true;
            return;
        }

        consumos.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.id_consumption}</td>
                <td>${item.userid || '-'}</td>
                <td>${item.action}</td>
                <td>${item.id_service}</td>
                <td>${item.cant_tokens}</td>
                <td>${item.balance}</td>
                <td>${item.date}</td>
            `;
            tableBody.appendChild(row);
        });
    };

    /**
     * 🔍 Obtener historial de consumo con filtros
     */
    const fetchData = () => {
        const serviceValue = filterService.value;
        const actionValue = filterAction.value;
        const fromValue = filterFrom.value;
        const toValue = filterTo.value;

        const args = {
            page: currentPage,
            limit: 10,
            servicio: serviceValue !== 'all' ? serviceValue : '',
            accion: actionValue !== 'all' ? actionValue : '',
            fecha_desde: fromValue || '',
            fecha_hasta: toValue || ''
        };

        console.log("📤 Enviando petición al WS con args:", JSON.stringify(args));

        Ajax.call([{
            methodname: 'aiprovider_datacurso_get_consumption_history',
            args: args
        }])[0].then(response => {
            console.log("📥 Respuesta del servidor:", response);

            const consumos = response?.consumption || [];
            renderTable(consumos);

            // 📑 Paginación
            const pagination = response?.pagination;
            if (pagination) {
                const { current_page, total_pages, total } = pagination;
                pageInfo.textContent = `Página ${current_page} de ${total_pages} (${total} registros)`;
                prevPageBtn.disabled = current_page <= 1;
                nextPageBtn.disabled = current_page >= total_pages;
            } else {
                pageInfo.textContent = '';
                prevPageBtn.disabled = true;
                nextPageBtn.disabled = true;
            }
        }).catch(async (e) => {
            const nodata = await getString('nodata', 'aiprovider_datacurso');
            tableBody.innerHTML = `<tr><td colspan="7">${nodata}</td></tr>`;
            console.error("❌ Error al obtener historial de consumo:", e);
        });
    };

    // 🎯 Filtros (reinician a página 1)
    [filterService, filterAction, filterFrom, filterTo].forEach(el => {
        el.addEventListener('change', () => {
            currentPage = 1;
            savePage(currentPage);
            fetchData();
        });
    });

    // ⏮ Página anterior
    prevPageBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            savePage(currentPage);
            fetchData();
        }
    });

    // ⏭ Página siguiente
    nextPageBtn.addEventListener('click', () => {
        currentPage++;
        savePage(currentPage);
        fetchData();
    });

    // 🚀 Carga inicial (servicios + acciones + datos)
    Promise.all([loadServices(), loadActions()])
        .then(() => {
            fetchData();
        })
        .catch(e => {
            console.error("❌ Error durante carga inicial:", e);
        });
};
