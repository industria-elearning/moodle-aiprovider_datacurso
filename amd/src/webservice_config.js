// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * AMD module to handle the Webservice configuration actions for Datacurso.
 * It performs setup, token regeneration, and registration calls and
 * displays progress via UI notifications and a simple log list.
 *
 * @module      aiprovider_datacurso/webservice_config
 * @copyright   2025 Wilber Narvaez <wilber@buendata.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Notification from "core/notification";
import {get_string as getString} from "core/str";
import {
  webserviceSetup,
  webserviceRegenerateToken,
  webserviceGetStatus,
} from "aiprovider_datacurso/repository";

/**
 * Initialize the webservice configuration.
 */
export function init() {
  const root = document.querySelector(
    '[data-region="aiprovider_datacurso/webservice-root"]'
  );
  if (!root) {
    return;
  }
  const btnSetup = root.querySelector(
    '[data-region="aiprovider_datacurso/webservice-btn-setup"]'
  );
  const btnRetry = root.querySelector(
    '[data-region="aiprovider_datacurso/webservice-btn-retry"]'
  );
  const btnRegenerate = root.querySelector(
    '[data-region="aiprovider_datacurso/webservice-btn-regenerate"]'
  );

  if (btnSetup) {
    btnSetup.addEventListener("click", setup);
  }
  if (btnRetry) {
    btnRetry.addEventListener("click", retry);
  }
  if (btnRegenerate) {
    btnRegenerate.addEventListener("click", regenerate);
  }

  // Initial refresh to ensure UI reflects current status when page loads.
  refreshStatus();
}

/**
 * Setup the webservice for Datacurso.
 */
async function setup() {
  try {
    const message = await getString('ws_step_setup', 'aiprovider_datacurso');
    log(message);
    const res = await webserviceSetup();
    if (res.messages && Array.isArray(res.messages)) {
      res.messages.forEach((m) => log(m));
    }
    await refreshStatus();
    Notification.addNotification({
      message: "Done: setup",
      type: "success",
    });
  } catch (e) {
    Notification.exception(e);
    log("Error: " + (e.message || e), "error");
  }
}

/**
 * Retry the webservice setup for Datacurso.
 */
async function retry() {
    try {
        const message = await getString('ws_step_token_retry', 'aiprovider_datacurso');
        log(message);
        const res = await webserviceSetup();
        res.messages.forEach((m) => log(m));
        await refreshStatus();
        Notification.addNotification({
          message: "Done: retry",
          type: "success",
        });
      } catch (e) {
        Notification.exception(e);
        log("Error: " + (e.message || e), "error");
      }
}

/**
 * Regenerate the webservice token for Datacurso.
 */
async function regenerate() {
  try {
    const message = await getString('ws_step_token_regenerating', 'aiprovider_datacurso');
    log(message);
    const res = await webserviceRegenerateToken();
    res.messages.forEach((m) => log(m));
    await refreshStatus();
    Notification.addNotification({
      message: "Done: regenerate",
      type: "success",
    });
  } catch (e) {
    Notification.exception(e);
    log("Error: " + (e.message || e), "error");
  }
}

/**
 * Log a message to the webservice log.
 *
 * @param {string} msg The message to log.
 * @param {string} type The type of the message.
 */
function log(msg, type = "info") {
  const list = document.querySelector(
    '[data-region="aiprovider_datacurso/webservice-log"]'
  );
  if (!list) {
    return;
  }
  const li = document.createElement("li");
  li.textContent = msg;
  li.classList.add("mb-1");
  if (type === "success") {
    li.classList.add("text-success");
  } else if (type === "error") {
    li.classList.add("text-danger");
  } else {
    li.classList.add("text-muted");
  }
  list.appendChild(li);
}

/**
 * Refresh UI from current status via AJAX without page reload.
 * @returns {Promise<void>}
 */
async function refreshStatus() {
  try {
    const status = await webserviceGetStatus();
    updateBadges(status);
    await updateHeader(status);
    await updateService(status);
    await updateRole(status);
    await updateToken(status);
    await updateRegistration(status);
  } catch (e) {
    // Silent fail to avoid blocking UI, but log for visibility.
    log("Status refresh error: " + (e.message || e), "error");
  }
}

/**
 * Update status badges for webservices/rest and assignment.
 * @param {Object} status
 */
function updateBadges(status) {
  const ws = document.querySelector('[data-region="aiprovider_datacurso/ws-enabled"]');
  const rest = document.querySelector('[data-region="aiprovider_datacurso/rest-enabled"]');
  if (ws) {
    setBadge(ws, !!status.webservicesenabled, 'enabled', 'disabled');
  }
  if (rest) {
    setBadge(rest, !!status.restenabled, 'enabled', 'disabled');
  }
  const assigned = document.querySelector('[data-region="aiprovider_datacurso/user-assigned-badge"]');
  if (assigned) {
    setBadge(assigned, !!status.userassigned, 'assigned', 'not_assigned');
  }
}

/**
 * Update external service badge and name.
 * @param {Object} status
 * @returns {Promise<void>}
 */
async function updateService(status) {
  const badge = document.querySelector('[data-region="aiprovider_datacurso/service-badge"]');
  const nameEl = document.querySelector('[data-region="aiprovider_datacurso/service-name"]');
  const exists = !!(status.service && (status.service.id || status.service.name));
  await setBadge(badge, exists, 'exists', 'not_created');
  if (nameEl) {
    nameEl.textContent = exists ? (status.service.name || '') : '';
  }
}

/**
 * Update the top header badge (Configured / Needs repair / Not configured).
 * Mirrors the Mustache header logic.
 * @param {Object} status
 * @returns {Promise<void>}
 */
async function updateHeader(status) {
  const el = document.querySelector('[data-region="aiprovider_datacurso/header-badge"]');
  if (!el) {
    return;
  }
  let cls = 'badge-danger';
  let key = 'not_configured';
  if (status.isconfigured) {
    cls = 'badge-success';
    key = 'configured';
  } else if (status.retryonly) {
    cls = 'badge-warning';
    key = 'needs_repair';
  } else if (status.registration && status.registration.lastsent) {
    if (status.needsrepair) {
      cls = 'badge-warning';
      key = 'needs_repair';
    } else {
      cls = 'badge-danger';
      key = 'not_configured';
    }
  } else {
    cls = 'badge-danger';
    key = 'not_configured';
  }
  el.classList.remove('badge-success', 'badge-warning', 'badge-danger');
  el.classList.add(cls);
  const label = await getString(key, 'aiprovider_datacurso');
  el.textContent = label;
}

/**
 * Update role label visibility/content.
 * @param {Object} status
 * @returns {Promise<void>}
 */
async function updateRole(status) {
  const roleEl = document.querySelector('[data-region="aiprovider_datacurso/role-label"]');
  if (!roleEl) {
    return;
  }
  if (status.userassigned && status.role && (status.role.shortname || status.role.name)) {
    roleEl.textContent = `${status.role.shortname || ''} — ${status.role.name || ''}`.trim();
    roleEl.classList.remove('d-none');
  } else {
    roleEl.textContent = '';
    roleEl.classList.add('d-none');
  }
}

/**
 * Update token badge and created label.
 * @param {Object} status
 * @returns {Promise<void>}
 */
async function updateToken(status) {
  const tokenBadge = document.querySelector('[data-region="aiprovider_datacurso/token-badge"]');
  const tokenCreated = document.querySelector('[data-region="aiprovider_datacurso/token-created"]');
  await setBadge(tokenBadge, !!status.tokenexists, 'exists', 'missing');
  if (tokenCreated) {
    if (status.tokenexists && status.tokencreated) {
      const label = await getString('created', 'aiprovider_datacurso');
      tokenCreated.textContent = `${label}: ${status.tokencreated}`;
      tokenCreated.classList.remove('d-none');
    } else {
      tokenCreated.textContent = '';
      tokenCreated.classList.add('d-none');
    }
  }
}

/**
 * Update registration badge and last sent line.
 * @param {Object} status
 * @returns {Promise<void>}
 */
async function updateRegistration(status) {
  const regBadge = document.querySelector('[data-region="aiprovider_datacurso/registration-badge"]');
  const regLast = document.querySelector('[data-region="aiprovider_datacurso/registration-lastsent"]');
  // Badge: success if verified, warning otherwise.
  if (regBadge) {
    regBadge.classList.remove('badge-success', 'badge-warning');
    regBadge.classList.add(status.registration?.verified ? 'badge-success' : 'badge-warning');
    const key = status.registration?.verified ? 'verified' : 'pending';
    const label = await getString(key, 'aiprovider_datacurso');
    regBadge.textContent = label;
  }
  if (regLast) {
    if (status.registration?.lastsent) {
      const label = await getString('last_sent', 'aiprovider_datacurso');
      const suffix = status.registration?.laststatus ? ` — ${status.registration.laststatus}` : '';
      regLast.textContent = `${label}: ${status.registration.lastsent}${suffix}`;
      regLast.classList.remove('d-none');
    } else {
      regLast.textContent = '';
      regLast.classList.add('d-none');
    }
  }
}

/**
 * Apply badge classes and label from lang strings.
 * @param {HTMLElement} el
 * @param {boolean} isOk
 * @param {string} okKey
 * @param {string} failKey
 * @returns {void}
 */
async function setBadge(el, isOk, okKey, failKey) {
  if (!el) {
    return;
  }
  el.classList.remove('badge-success', 'badge-danger');
  el.classList.add(isOk ? 'badge-success' : 'badge-danger');
  const label = await getString(isOk ? okKey : failKey, 'aiprovider_datacurso');
  el.textContent = label;
}
