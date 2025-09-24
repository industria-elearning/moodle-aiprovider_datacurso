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

import Chart from 'core/chartjs';

export const init = (consumos) => {
    // Dataset de tokens por día
    const labels = consumos.map(c => c.fecha);
    const tokens = consumos.map(c => c.cantidad_tokens);

    const ctx1 = document.getElementById('chart-tokens-by-day');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Tokens consumidos',
                data: tokens,
                borderColor: '#0073e6',
                fill: false
            }]
        }
    });

    // Dataset de acciones
    const acciones = {};
    consumos.forEach(c => {
        acciones[c.accion] = (acciones[c.accion] || 0) + c.cantidad_tokens;
    });

    const ctx2 = document.getElementById('chart-actions');
    new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: Object.keys(acciones),
            datasets: [{
                data: Object.values(acciones),
                backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0']
            }]
        }
    });
};
