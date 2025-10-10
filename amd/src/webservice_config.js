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
