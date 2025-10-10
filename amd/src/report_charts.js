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

/* eslint-disable */
import Ajax from 'core/ajax';
import Chart from 'core/chartjs';

export const init = () => {
    const tokensAvailable = document.getElementById('tokens-available');
    const tokensConsumed = document.getElementById('tokens-consumed');

    let chartBar, chartPie, chartDay;

    // ============================================================
    // 🔹 1. CARGA INICIAL: SALDO Y SERVICIOS
    // ============================================================
    Promise.all([
        Ajax.call([{ methodname: 'aiprovider_datacurso_get_tokens_saldo', args: {} }])[0],
        Ajax.call([{ methodname: 'aiprovider_datacurso_get_services', args: {} }])[0],
    ]).then(([saldoResponse, servicesResponse]) => {

        const saldo = saldoResponse?.saldo_actual || 0;
        tokensAvailable.textContent = saldo;

        const servicios = servicesResponse?.services || [];
        initCharts(servicios);

    }).catch(err => console.error("❌ Error inicial:", err));

    // ============================================================
    // 📊 INICIALIZAR GRÁFICAS CON FILTROS
    // ============================================================
    const initCharts = (servicios) => {
        const filterBar = document.getElementById('filter-service-bar');
        const filterPie = document.getElementById('filter-service-pie');
        const filterStart = document.getElementById('filter-start-date');
        const filterEnd = document.getElementById('filter-end-date');

        // Llenar selects de servicios
        const fillSelect = (select) => {
            select.innerHTML = '<option value="">Todos</option>';
            if (servicios?.length) {
                servicios.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.name;
                    opt.textContent = s.name;
                    select.appendChild(opt);
                });
            }
        };

        fillSelect(filterBar);
        fillSelect(filterPie);

        // Render inicial
        updateBarChart();
        updatePieChart();
        updateDayChart();

        // Listeners de filtros
        filterBar.addEventListener('change', updateBarChart);
        filterPie.addEventListener('change', updatePieChart);
        filterStart.addEventListener('change', updateDayChart);
        filterEnd.addEventListener('change', updateDayChart);
    };

    // ============================================================
    // 🔹 FUNCIONES DE WS
    // ============================================================
const fetchConsumptionData = async (params = {}) => {
    const defaults = {
        servicio: "",
        accion: "",
        fechadesde: "",
        fechahasta: ""
    };
    const finalParams = { ...defaults, ...params };

    try {
        const response = await Ajax.call([{
            methodname: 'aiprovider_datacurso_get_all_consumption',
            args: finalParams
        }])[0];

        if (response.status !== 'success') {
            console.warn("⚠️ WS devolvió estado:", response.status);
            return [];
        }
        console.log("response", response);
        return response.consumption || [];

    } catch (error) {
        console.error("❌ Error al obtener consumos:", error);
        return [];
    }
};
    // ============================================================
    // 📊 GRÁFICA DE BARRAS: Consumo por mes + filtro por servicio
    // ============================================================
    const updateBarChart = async () => {
        const servicio = document.getElementById('filter-service-bar').value;

        const data = await fetchConsumptionData({
            servicio: servicio !== 'all' ? servicio : ''
        });

        console.log("respueta barra",  data);

        // Calcular totales por mes
        const byMonth = {};
        data.forEach(c => {
            const month = c.date.substring(0, 7);
            byMonth[month] = (byMonth[month] || 0) + c.cant_tokens;
        });

        // Actualizar tarjeta de total consumido
        const totalTokens = data.reduce((sum, c) => sum + (c.cant_tokens || 0), 0);
        tokensConsumed.textContent = totalTokens;

        // Renderizar gráfico
        const ctx = document.getElementById('chart-tokens-by-month');
        if (chartBar) chartBar.destroy();

        chartBar = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(byMonth),
                datasets: [{
                    label: 'Tokens consumidos por mes',
                    data: Object.values(byMonth),
                    backgroundColor: '#0073e6',
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    };

    // ============================================================
    // 📊 GRÁFICA CIRCULAR: Distribución por acción + filtro servicio
    // ============================================================
    const updatePieChart = async () => {
        const servicio = document.getElementById('filter-service-pie').value;

        const data = await fetchConsumptionData({
            servicio: servicio !== 'all' ? servicio : null
        });

        console.log("respueta pai ",  data);

        const byAction = {};
        data.forEach(c => {
            byAction[c.action] = (byAction[c.action] || 0) + c.cant_tokens;
        });

        const ctx = document.getElementById('chart-actions');
        if (chartPie) chartPie.destroy();

        chartPie = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(byAction),
                datasets: [{
                    data: Object.values(byAction),
                    backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0'],
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    };

    // ============================================================
    // 📊 GRÁFICA DE LÍNEA: Consumo diario + filtros de fechas
    // ============================================================
const updateDayChart = async () => {
    const fechadesde = document.getElementById('filter-start-date').value;
    const fechahasta = document.getElementById('filter-end-date').value;

    const data = await fetchConsumptionData({
        fechadesde: fechadesde || "",
        fechahasta: fechahasta || ""
    });

    console.log("respuesta day", data);

    // Agrupar tokens por día
    const byDay = {};
    data.forEach(c => {
        const day = c.date.substring(0, 10);
        byDay[day] = (byDay[day] || 0) + c.cant_tokens;
    });

    // 🔹 Ordenar fechas de lo más antiguo a lo más reciente
    const labels = Object.keys(byDay).sort((a, b) => new Date(a) - new Date(b));
    const values = labels.map(day => byDay[day]);

    console.log(values)

    // Renderizar gráfico
    const ctx = document.getElementById('chart-tokens-by-day');
    if (chartDay) chartDay.destroy();

    chartDay = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Tokens consumidos por día',
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
                x: {
                    title: { display: true, text: 'Fecha' }
                },
                y: {
                    title: { display: true, text: 'Tokens consumidos' },
                    beginAtZero: true
                }
            }
        }
    });
};
};
