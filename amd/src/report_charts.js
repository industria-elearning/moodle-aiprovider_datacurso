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
 * TODO describe module report
 *
 * @module     aiprovider_datacurso/report_charts
 * @copyright  2025 Industria Elearning <info@industriaelearning.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* eslint-disable */

import Ajax from 'core/ajax';
import Chart from 'core/chartjs';
import {get_string as getString} from 'core/str';

export const init = () => {
    console.log("📊 Report init");

    const tokensAvailable = document.getElementById('tokens-available');
    const tokensConsumed = document.getElementById('tokens-consumed');
    const tableBody = document.getElementById('consumption-table-body');

    let consumos = [];
    let chartBar, chartPie;

    // 🔹 1. Llamadas a los WS
    Promise.all([
        Ajax.call([{ methodname: 'aiprovider_datacurso_get_consumption_history', args: {} }])[0],
        Ajax.call([{ methodname: 'aiprovider_datacurso_get_tokens_saldo', args: {} }])[0],
    ]).then(async ([historyResponse, saldoResponse]) => {
        consumos = historyResponse?.consumos || [];
        const saldo = saldoResponse?.saldo_actual || 0;

        // 🔹 2. Actualizar cards
        tokensAvailable.textContent = saldo;
        tokensConsumed.textContent = consumos.reduce((sum, c) => sum + (c.cantidad_tokens || 0), 0);

        // 🔹 3. Renderizar tabla
        renderTable(consumos, tableBody);

        // 🔹 4. Inicializar filtros y charts
        initCharts(consumos);

    }).catch(err => {
        console.error("❌ Error WS", err);
    });

    // 🔹 Tabla dinámica
    const renderTable = async (data, container) => {
        container.innerHTML = '';
        if (!data.length) {
            const nodata = await getString('nodata', 'aiprovider_datacurso');
            container.innerHTML = `<tr><td colspan="7" class="text-center">${nodata}</td></tr>`;
            return;
        }
        data.forEach(item => {
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
            container.appendChild(row);
        });
    };

    // 🔹 Charts con filtros
    const initCharts = (data) => {
        const filterBar = document.getElementById('filter-service-bar');
        const filterPie = document.getElementById('filter-service-pie');

        // Rellenar selects
        const servicios = [...new Set(data.map(c => c.servicio))];
        servicios.forEach(s => {
            filterBar.innerHTML += `<option value="${s}">${s}</option>`;
            filterPie.innerHTML += `<option value="${s}">${s}</option>`;
        });

        // Inicial render
        renderCharts(data);

        // Listeners
        filterBar.addEventListener('change', () => renderCharts(data));
        filterPie.addEventListener('change', () => renderCharts(data));
    };

    const renderCharts = (data) => {
        const filterBar = document.getElementById('filter-service-bar').value;
        const filterPie = document.getElementById('filter-service-pie').value;

        let filteredData = data;
        if (filterBar !== 'ALL' || filterPie !== 'ALL') {
            filteredData = data.filter(c =>
                (filterBar === 'ALL' || c.servicio === filterBar) &&
                (filterPie === 'ALL' || c.servicio === filterPie)
            );
        }

        // Agrupar por mes
        const byMonth = {};
        filteredData.forEach(c => {
            const month = c.fecha.substring(0, 7); // yyyy-mm
            byMonth[month] = (byMonth[month] || 0) + c.cantidad_tokens;
        });

        // Agrupar por acción
        const byAction = {};
        filteredData.forEach(c => {
            byAction[c.accion] = (byAction[c.accion] || 0) + c.cantidad_tokens;
        });

        // Chart bar
        const ctx1 = document.getElementById('chart-tokens-by-month');
        if (chartBar) chartBar.destroy();
        chartBar = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: Object.keys(byMonth),
                datasets: [{
                    label: 'Tokens consumidos',
                    data: Object.values(byMonth),
                    backgroundColor: '#0073e6'
                }]
            }
        });

        // Chart pie
        const ctx2 = document.getElementById('chart-actions');
        if (chartPie) chartPie.destroy();
        chartPie = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: Object.keys(byAction),
                datasets: [{
                    data: Object.values(byAction),
                    backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0']
                }]
            }
        });
    };
};
