<?php
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
 * Plugin strings are defined here.
 *
 * @package     aiprovider_datacurso
 * @category    string
 * @copyright   Josue <josue@datacurso.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['action'] = 'Action';
$string['apikey'] = 'API key';
$string['apikey_desc'] = 'Enter the API key from your Datacurso service to connect the AI.';
$string['apiurl'] = 'Base API URL';
$string['apiurl_desc'] = 'Enter the base URL of the service to connect to the Datacurso API.';
$string['chart_actions'] = 'Token distribution by service';
$string['chart_tokens_by_day'] = 'Token consumption by day';
$string['chart_tokens_by_month'] = 'Number of tokens consumed per month';
$string['enableglobalratelimit'] = 'Enable global limit';
$string['enableglobalratelimit_desc'] = 'If enabled, a global request limit per hour will be applied for all users.';
$string['enableuserratelimit'] = 'Enable per-user limit';
$string['enableuserratelimit_desc'] = 'If enabled, each user will have an hourly request limit.';
$string['globalratelimit'] = 'Global request limit';
$string['globalratelimit_desc'] = 'Maximum number of requests allowed per hour for the entire system.';
$string['id'] = 'ID';
$string['licensekey'] = 'License key';
$string['licensekey_desc'] = 'Enter the provided license key (example: LICENSE_KEY_CLIENT).';
$string['link_consumptionhistory'] = 'Token consumption history';
$string['link_generalreport'] = 'General report';
$string['link_listplugings'] = 'Datacurso plugins list';
$string['link_report_statistic'] = 'General statistics report';
$string['nodata'] = 'No information found';
$string['orgid'] = 'Organization ID';
$string['orgid_desc'] = 'Enter the identifier of your organization in the Datacurso service.';
$string['pluginname'] = 'Datacurso Provider';
$string['remainingtokens'] = 'Remaining balance';
$string['service'] = 'Service';
$string['tokens_available'] = 'Available tokens';
$string['tokensused'] = 'Tokens used';
$string['tokenthreshold'] = 'Token threshold';
$string['tokenthreshold_desc'] = 'Number of tokens from which a notification will be shown to purchase more.';
$string['total_consumed'] = 'Total consumed';
$string['userid'] = 'User';
$string['userratelimit'] = 'Per-user request limit';
$string['userratelimit_desc'] = 'Maximum number of requests allowed per hour for each individual user.';
$string['workplace'] = 'Is this Moodle Workplace?';
$string['workplace_desc'] = 'Defines whether the X-Workplace header should be sent with value true (Workplace) or false (Standard Moodle).';
