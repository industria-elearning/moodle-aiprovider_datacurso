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

import Ajax from "core/ajax";
import { get_string as getString } from "core/str";
import Templates from "core/templates";
import Notification from "core/notification";
import { getConsumptionHistory } from "aiprovider_datacurso/repository";

export const init = async () => {
  // Elements DOM
  const filterService = document.getElementById("filter-service");
  const filterAction = document.getElementById("filter-action");
  const filterFrom = document.getElementById("filter-date-from");
  const filterTo = document.getElementById("filter-date-to");
  const prevPageBtn = document.getElementById("prev-page");
  const nextPageBtn = document.getElementById("next-page");
  const pageInfo = document.getElementById("page-info");
  const tableRegionSelector =
    '[data-region="aiprovider_datacurso/consumption-table"]';

  // Order
  let currentSortField = "";
  let currentSortDir = "asc";

  // Select and input for limit and page
  const limitSelect = document.getElementById("filter-limit");
  const pageInput = document.getElementById("filter-page");

  // Init status
  let currentPage = parseInt(sessionStorage.getItem("consumptionPage")) || 1;
  let currentLimit = parseInt(sessionStorage.getItem("consumptionLimit")) || 10;

  const savePage = (page) => sessionStorage.setItem("consumptionPage", page);
  const saveLimit = (limit) =>
    sessionStorage.setItem("consumptionLimit", limit);

  const updateSortIndicators = () => {
    document
      .querySelectorAll(`${tableRegionSelector} .sort-icon`)
      .forEach((icon) => {
        icon.className = "fa fa-sort sort-icon";
      });

    if (!currentSortField) {
      return;
    }

    const activeHeader = document.querySelector(
      `${tableRegionSelector} [data-sort="${currentSortField}"] .sort-icon`
    );
    if (activeHeader) {
      activeHeader.className =
        currentSortDir === "asc"
          ? "fa fa-sort-up sort-icon"
          : "fa fa-sort-down sort-icon";
    }
  };

  const bindSortingHandlers = () => {
    document
      .querySelectorAll(`${tableRegionSelector} .sortable`)
      .forEach((header) => {
        header.addEventListener("click", () => {
          const field = header.dataset.sort;
          if (!field) {
            return;
          }

          if (currentSortField === field) {
            currentSortDir = currentSortDir === "asc" ? "desc" : "asc";
          } else {
            currentSortField = field;
            currentSortDir = "asc";
          }

          updateSortIndicators();
          currentPage = 1;
          savePage(currentPage);
          fetchData();
        });
      });

    updateSortIndicators();
  };

  const loadServices = async () => {
    try {
      const response = await Ajax.call([
        {
          methodname: "aiprovider_datacurso_get_services",
          args: {},
        },
      ])[0];

      if (response?.services?.length) {
        response.services.forEach((s) => {
          const opt = document.createElement("option");
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
      const response = await Ajax.call([
        {
          methodname: "aiprovider_datacurso_get_actions",
          args: {},
        },
      ])[0];

      if (response?.actions?.length) {
        response.actions.forEach((a) => {
          const opt = document.createElement("option");
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

  const renderTable = async (listconsumptions, { loading = false } = {}) => {
    const container = document.querySelector(tableRegionSelector);
    if (!container) {
      return;
    }

    const consumptions = Array.isArray(listconsumptions)
      ? listconsumptions
      : [];
    const context = {
      initialized: !loading,
      consumptions: consumptions,
    };

    try {
      const render = await Templates.renderForPromise(
        "aiprovider_datacurso/consumption_row",
        context
      );
      await Templates.replaceNodeContents(container, render.html, render.js);
      bindSortingHandlers();
    } catch (error) {
      Notification.exception(error);
      const fallback = await Templates.renderForPromise(
        "aiprovider_datacurso/consumption_row",
        {
          initialized: true,
          consumptions: [],
        }
      );
      await Templates.replaceNodeContents(
        container,
        fallback.html,
        fallback.js
      );
      bindSortingHandlers();
    }
  };

  // Get history with filters
  const fetchData = async () => {
    const serviceValue = filterService.value;
    const actionValue = filterAction.value;
    const fromValue = filterFrom.value;
    const toValue = filterTo.value;

    const args = {
      page: currentPage,
      limit: currentLimit,
      service: serviceValue !== "all" ? serviceValue : "",
      action: actionValue !== "all" ? actionValue : "",
      fromdate: fromValue || "",
      todate: toValue || "",
    };

    if (currentSortField) {
      args.shor = currentSortField;
      args.shordir = currentSortDir;
    }

    await renderTable([], { loading: true });

    const response = await getConsumptionHistory(args);

    const consumptions = response?.consumption || [];
    await renderTable(consumptions);

    const pagination = response?.pagination;
    if (pagination) {
      const { current_page, total_pages, total } = pagination;
      const pageInfoText = await getString("pageinfo", "aiprovider_datacurso", {
        current: current_page,
        totalpages: total_pages,
        total: total,
      });
      pageInfo.textContent = pageInfoText;
      if (pageInput) {
        pageInput.value = current_page;
      }
      prevPageBtn.disabled = current_page <= 1;
      nextPageBtn.disabled = current_page >= total_pages;
    } else {
      pageInfo.textContent = "";
      prevPageBtn.disabled = true;
      nextPageBtn.disabled = true;
    }
  };

  // Event listeners for filters
  [filterService, filterAction, filterFrom, filterTo].forEach((el) => {
    el.addEventListener("change", () => {
      currentPage = 1;
      savePage(currentPage);
      fetchData();
    });
  });

  prevPageBtn.addEventListener("click", () => {
    if (currentPage > 1) {
      currentPage--;
      savePage(currentPage);
      fetchData();
    }
  });

  nextPageBtn.addEventListener("click", () => {
    currentPage++;
    savePage(currentPage);
    fetchData();
  });

  if (limitSelect) {
    limitSelect.value = currentLimit;
    limitSelect.addEventListener("change", () => {
      currentLimit = parseInt(limitSelect.value);
      saveLimit(currentLimit);
      currentPage = 1;
      savePage(currentPage);
      fetchData();
    });
  }

  if (pageInput) {
    pageInput.addEventListener("change", () => {
      const newPage = parseInt(pageInput.value);
      if (newPage && newPage > 0) {
        currentPage = newPage;
        savePage(currentPage);
        fetchData();
      }
    });
  }

  // Initial load
  Promise.all([loadServices(), loadActions()])
    .then(() => fetchData())
    .catch(Notification.exception);

  bindSortingHandlers();
};
