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
 * Report charts module (with advanced table).
 *
 * @module     aiprovider_datacurso/report_charts
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* eslint-disable */
import Ajax from 'core/ajax';
import Chart from 'core/chartjs';
import { get_string as getString } from 'core/str';

export const init = () => {

    const tokensAvailable = document.getElementById('tokens-available');
    const tokensConsumed = document.getElementById('tokens-consumed');
    const tableBody = document.getElementById('consumption-table-body');

    // Filtros y paginación
    const filterService = document.getElementById('filter-service');
    const filterAction = document.getElementById('filter-action');
    const prevPageBtn = document.getElementById('prev-page');
    const nextPageBtn = document.getElementById('next-page');
    const pageInfo = document.getElementById('page-info');

    let consumos = [];
    let filteredConsumos = [];
    let currentPage = 1;
    const rowsPerPage = 10;

    let chartBar, chartPie, chartDay;

    // 🔹 1. WS calls
    Promise.all([
        Ajax.call([{ methodname: 'aiprovider_datacurso_get_consumption_history', args: {} }])[0],
        Ajax.call([{ methodname: 'aiprovider_datacurso_get_tokens_saldo', args: {} }])[0],
    ]).then(async ([historyResponse, saldoResponse]) => {

        console.log("response", historyResponse)
        consumos = historyResponse?.consumption || [];
        const saldo = saldoResponse?.saldo_actual || 0;

        // 🔹 2. Update cards
        tokensAvailable.textContent = saldo;
        tokensConsumed.textContent = consumos.reduce((sum, c) => sum + (c.cantidad_tokens || 0), 0);

        // 🔹 3. Initialize filters for table
        initTable(consumos);

        // 🔹 4. Initialize charts
        initCharts(consumos);

    }).catch(err => {
        console.error("❌ WS Error", err);
    });


    // ============================================================
    // 📊 CHARTS SECTION
    // ============================================================
    const initCharts = (data) => {
        const filterBar = document.getElementById('filter-service-bar');
        const filterPie = document.getElementById('filter-service-pie');

        // Fill selects
        const servicios = [...new Set(data.map(c => c.id_servicio))];
        servicios.forEach(s => {
            filterBar.innerHTML += `<option value="${s}">${s}</option>`;
            filterPie.innerHTML += `<option value="${s}">${s}</option>`;
        });

        // Initial render
        renderBarChart(data);
        renderPieChart(data);
        renderDayChart(data);

        // Listeners → each chart listens ONLY to its filter
        filterBar.addEventListener('change', () => renderBarChart(data));
        filterPie.addEventListener('change', () => renderPieChart(data));

        // Date filters for daily chart
        document.getElementById('filter-start-date').addEventListener('change', () => renderDayChart(data));
        document.getElementById('filter-end-date').addEventListener('change', () => renderDayChart(data));
    };

    const renderBarChart = (data) => {
        const filterValue = document.getElementById('filter-service-bar').value;
        let filteredData = filterValue === 'ALL' ? data : data.filter(c => c.id_servicio === filterValue);

        const byMonth = {};
        filteredData.forEach(c => {
            const month = c.fecha.substring(0, 7);
            byMonth[month] = (byMonth[month] || 0) + c.cantidad_tokens;
        });

        const ctx1 = document.getElementById('chart-tokens-by-month');
        if (chartBar) chartBar.destroy();
        chartBar = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: Object.keys(byMonth),
                datasets: [{
                    label: 'Tokens consumed',
                    data: Object.values(byMonth),
                    backgroundColor: '#0073e6'
                }]
            }
        });
    };

    const renderPieChart = (data) => {
        const filterValue = document.getElementById('filter-service-pie').value;
        let filteredData = filterValue === 'ALL' ? data : data.filter(c => c.id_servicio === filterValue);

        const byAction = {};
        filteredData.forEach(c => {
            byAction[c.accion] = (byAction[c.accion] || 0) + c.cantidad_tokens;
        });

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

    const renderDayChart = (data) => {
        const startDate = document.getElementById('filter-start-date').value;
        const endDate = document.getElementById('filter-end-date').value;

        let filteredData = data;
        if (startDate) filteredData = filteredData.filter(c => c.fecha >= startDate);
        if (endDate) filteredData = filteredData.filter(c => c.fecha <= endDate);

        const byDay = {};
        filteredData.forEach(c => {
            const day = c.fecha.substring(0, 10);
            byDay[day] = (byDay[day] || 0) + c.cantidad_tokens;
        });

        const ctx3 = document.getElementById('chart-tokens-by-day');
        if (chartDay) chartDay.destroy();
        chartDay = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: Object.keys(byDay),
                datasets: [{
                    label: 'Tokens consumed per day',
                    data: Object.values(byDay),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40,167,69,0.2)',
                    fill: true,
                }]
            }
        });
    };
};
