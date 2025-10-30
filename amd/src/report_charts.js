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
 * Report charts module.
 *
 * @module     aiprovider_datacurso/report_charts
 * @copyright  2025 Industria Elearning
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Ajax from 'core/ajax';
import Chart from 'core/chartjs';
import { get_string as getString } from 'core/str';
import Notification from 'core/notification';

export const init = async () => {

    const date = await getString('date', 'core');
    const creditsConsumedMonth = await getString('tokensconsumedmonth', 'aiprovider_datacurso');
    const creditsConsumedDay = await getString('tokensconsumedday', 'aiprovider_datacurso');
    const creditsConsumed = await getString('tokensconsumed', 'aiprovider_datacurso');

    const tokensAvailable = document.getElementById('tokens-available');
    const tokensConsumed = document.getElementById('tokens-consumed');

    let chartBar, chartPie, chartDay;
    let cachedData = [];

    Promise.all([
        Ajax.call([{ methodname: 'aiprovider_datacurso_get_credits_balance', args: {} }])[0],
        Ajax.call([{ methodname: 'aiprovider_datacurso_get_services', args: {} }])[0],
        Ajax.call([{ methodname: 'aiprovider_datacurso_get_all_consumption', args: {} }])[0],
    ])
        .then(([balanceResponse, servicesResponse, consumptionResponse]) => {
            const balance = balanceResponse?.balance || 0;
            tokensAvailable.textContent = balance;
            const services = servicesResponse?.services || [];
            cachedData = consumptionResponse?.consumption || [];
            initCharts(services);
        })
        .catch(Notification.exception);

    // init grafic
    const initCharts = (services) => {
        const filterBar = document.getElementById('filter-service-bar');
        const filterPie = document.getElementById('filter-service-pie');
        const filterStart = document.getElementById('filter-start-date');
        const filterEnd = document.getElementById('filter-end-date');

        const fillSelect = (select) => {
            if (services?.length) {
                services.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.name;
                    opt.textContent = s.name;
                    select.appendChild(opt);
                });
            }
        };

        fillSelect(filterBar);
        fillSelect(filterPie);

        // Render init used cachedData
        renderBarChart(cachedData);
        renderPieChart(cachedData);
        renderDayChart(cachedData);

        // Listeners filters
        filterBar.addEventListener('change', () => updateBarChart());
        filterPie.addEventListener('change', () => updatePieChart());
        filterStart.addEventListener('change', () => updateDayChart());
        filterEnd.addEventListener('change', () => updateDayChart());
    };

    // functions ws
    const fetchConsumptionData = async (params = {}) => {
        const defaults = {
            service: "",
            action: "",
            fromdate: "",
            todate: ""
        };
        const finalParams = { ...defaults, ...params };

        try {
            const response = await Ajax.call([{
                methodname: 'aiprovider_datacurso_get_all_consumption',
                args: finalParams
            }])[0];

            if (response.status !== 'success') {
                return [];
            }
            return response.consumption || [];

        } catch (error) {
            Notification.exception(error);
            return [];
        }
    };

    // grafic bar
    const renderBarChart = (data) => {
        const byMonth = {};
        data.forEach(c => {
            const month = c.date.substring(0, 7);
            byMonth[month] = (byMonth[month] || 0) + c.cant_tokens;
        });

        const totalTokens = data.reduce((sum, c) => sum + (c.cant_tokens || 0), 0);
        tokensConsumed.textContent = totalTokens;

        const ctx = document.getElementById('chart-tokens-by-month');
        if (chartBar) {
            chartBar.destroy();
        }

        chartBar = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(byMonth),
                datasets: [{
                    label: creditsConsumedMonth,
                    data: Object.values(byMonth),
                    backgroundColor: '#0073e6',
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    };

    const updateBarChart = async () => {
        const service = document.getElementById('filter-service-bar').value;
        if (!service) {
            return renderBarChart(cachedData);
        }
        const data = await fetchConsumptionData({ service });
        renderBarChart(data);
    };

    // grafic pie
    const renderPieChart = (data) => {
        const byAction = {};
        data.forEach(c => {
            byAction[c.action] = (byAction[c.action] || 0) + c.cant_tokens;
        });

        const ctx = document.getElementById('chart-actions');
        if (chartPie) {
            chartPie.destroy();
        }

        chartPie = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(byAction),
                datasets: [{
                    data: Object.values(byAction),
                    backgroundColor: [
                        '#36A2EB',
                        '#FF6384',
                        '#f1d48bff',
                        '#5ddcdcff',
                        '#049930ff',
                        '#0b6eb0ff',
                        '#d10f39ff',
                        '#7c611dff',
                        '#ee9610ff',
                        '#a50562ff',
                        '#022082ff',
                        '#efef21ff',
                        '#3f4646ff',
                        '#8f9191ff',
                        '#0c361bff',
                        '#bd836cff'
                    ],
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    };

    const updatePieChart = async () => {
        const service = document.getElementById('filter-service-pie').value;
        if (!service) {
            return renderPieChart(cachedData);
        }

        const data = await fetchConsumptionData({ service });
        renderPieChart(data);
    };

    // grafic day
    const renderDayChart = (data) => {
        const byDay = {};
        data.forEach(c => {
            const day = c.date.substring(0, 10);
            byDay[day] = (byDay[day] || 0) + c.cant_tokens;
        });

        const labels = Object.keys(byDay).sort((a, b) => new Date(a) - new Date(b));
        const values = labels.map(day => byDay[day]);

        const ctx = document.getElementById('chart-tokens-by-day');
        if (chartDay) {
            chartDay.destroy();
        }

        chartDay = new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: creditsConsumedDay,
                    data: values,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40,167,69,0.2)',
                    fill: true,
                    tension: 0.2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { title: { display: true, text: date } },
                    y: { title: { display: true, text: creditsConsumed }, beginAtZero: true }
                }
            }
        });
    };

    const updateDayChart = async () => {
        const fromdate = document.getElementById('filter-start-date').value;
        const todate = document.getElementById('filter-end-date').value;

        if (!fromdate && !todate) {
            return renderDayChart(cachedData);
        }

        const data = await fetchConsumptionData({ fromdate, todate });
        renderDayChart(data);
    };
};
