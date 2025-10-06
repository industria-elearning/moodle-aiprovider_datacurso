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
$string['assigned'] = 'Assigned';
$string['chart_actions'] = 'Token distribution by service';
$string['chart_tokens_by_day'] = 'Token consumption by day';
$string['chart_tokens_by_month'] = 'Number of tokens consumed per month';
$string['configured'] = 'Configured';
$string['contextwstoken'] = 'Web service token for course context';
$string['contextwstoken_desc'] = 'Token used by the AI to retrieve course information (context). Stored securely. Create/manage tokens in Site administration > Server > Web services > Manage tokens.';
$string['created'] = 'Created';
$string['datacurso:manage'] = 'Manage AI provider settings';
$string['datacurso:use'] = 'Use Datacurso AI services';
$string['datacurso:viewreports'] = 'View AI usage reports';
$string['disabled'] = 'Disabled';
$string['enabled'] = 'Enabled';
$string['enableglobalratelimit'] = 'Enable global limit';
$string['enableglobalratelimit_desc'] = 'If enabled, a global request limit per hour will be applied for all users.';
$string['enableuserratelimit'] = 'Enable per-user limit';
$string['enableuserratelimit_desc'] = 'If enabled, each user will have an hourly request limit.';
$string['exists'] = 'Exists';
$string['globalratelimit'] = 'Global request limit';
$string['globalratelimit_desc'] = 'Maximum number of requests allowed per hour for the entire system.';
$string['goto'] = 'Go to Report';
$string['id'] = 'ID';
$string['last_sent'] = 'Last sent';
$string['licensekey'] = 'License key';
$string['licensekey_desc'] = 'Enter the provided license key (example: LICENSE_KEY_CLIENT).';
$string['link_consumptionhistory'] = 'Token consumption history';
$string['link_generalreport'] = 'General report';
$string['link_generalreport_datacurso'] = 'General report Datacurso AI';
$string['link_listplugings'] = 'Datacurso plugins list';
$string['link_report_statistic'] = 'General statistics report';
$string['link_webservice_config'] = 'Datacurso webservice setup';
$string['live_log'] = 'Live log';
$string['missing'] = 'Missing';
$string['needs_repair'] = 'Needs repair';
$string['nodata'] = 'No information found';
$string['not_assigned'] = 'Not assigned';
$string['not_configured'] = 'Not configured';
$string['not_created'] = 'Not created';
$string['orgid'] = 'Organization ID';
$string['orgid_desc'] = 'Enter the identifier of your organization in the Datacurso service.';
$string['pending'] = 'Pending';
$string['pluginname'] = 'Datacurso Provider';
$string['privacy:metadata'] = 'The Datacurso AI Provider plugin does not store any personal data locally. All data is processed by external Datacurso AI services.';
$string['privacy:metadata:datacurso_ai_services'] = 'Data sent to Datacurso AI services for processing.';
$string['privacy:metadata:datacurso_ai_services:request_data'] = 'The data sent to the AI service for processing.';
$string['privacy:metadata:datacurso_ai_services:response_data'] = 'The response received from the AI service.';
$string['privacy:metadata:datacurso_ai_services:timestamp'] = 'When the AI request was made.';
$string['privacy:metadata:datacurso_ai_services:tokens_consumed'] = 'Number of tokens consumed in the request.';
$string['privacy:metadata:datacurso_ai_services:userid'] = 'The user ID making the AI request.';
$string['registration_error'] = 'Last error';
$string['registration_last'] = 'Registration';
$string['registration_lastsent'] = 'Last sent';
$string['registration_notverified'] = 'Registration not verified';
$string['registration_status'] = 'Last status';
$string['registration_verified'] = 'Registration verified';
$string['registrationapibearer'] = 'Registration bearer token';
$string['registrationapibearer_desc'] = 'Bearer token used to authenticate the registration request.';
$string['registrationapiurl'] = 'Registration endpoint URL';
$string['registrationapiurl_desc'] = 'Endpoint to receive the site registration payload. Default: http://localhost:8001/register-site';
$string['registrationsettings'] = 'Registration API';
$string['remainingtokens'] = 'Remaining balance';
$string['rest_enabled'] = 'REST protocol enabled';
$string['service'] = 'Service';
$string['tokens_available'] = 'Available tokens';
$string['tokensused'] = 'Tokens used';
$string['tokenthreshold'] = 'Token threshold';
$string['tokenthreshold_desc'] = 'Number of tokens from which a notification will be shown to purchase more.';
$string['total_consumed'] = 'Total consumed';
$string['userid'] = 'User';
$string['userratelimit'] = 'Per-user request limit';
$string['userratelimit_desc'] = 'Maximum number of requests allowed per hour for each individual user.';
$string['verified'] = 'Verified';
$string['webserviceconfig_current'] = 'Current configuration';
$string['webserviceconfig_desc'] = 'Automatically configures a dedicated webservice for the Datacurso AI service, enabling it to securely extract platform information such as user basic data, courses, and activities for better AI contextualization. This setup creates a service user, assigns the necessary role, configures the external service, generates a secure token, and enables the REST protocol in one click. Note: The token value is not displayed for security reasons.';
$string['webserviceconfig_heading'] = 'Automatic webservice setup';
$string['webserviceconfig_site'] = 'Site information';
$string['webserviceconfig_status'] = 'Status';
$string['webserviceconfig_title'] = 'Datacurso webservice configuration';
$string['workplace'] = 'Is this Moodle Workplace?';
$string['workplace_desc'] = 'Defines whether the X-Workplace header should be sent with value true (Workplace) or false (Standard Moodle).';
$string['ws_activity'] = 'Activity log';
$string['ws_btn_regenerate'] = 'Regenerate token';
$string['ws_btn_retry'] = 'Retry configuration';
$string['ws_btn_setup'] = 'Configure webservice';
$string['ws_enabled'] = 'Web services enabled';
$string['ws_error_missing_setup'] = 'Service or user not found. Run setup first.';
$string['ws_error_missing_token'] = 'Token not found. Generate it first.';
$string['ws_error_regenerate_token'] = 'Error regenerating token.';
$string['ws_error_registration'] = 'Error registering webservice token.';
$string['ws_error_setup'] = 'Error configuring webservice.';
$string['ws_role'] = 'Service role';
$string['ws_service'] = 'External service';
$string['ws_step_enableauth'] = 'Enabling webservices auth plugin…';
$string['ws_step_enablerest'] = 'Enabling REST protocol…';
$string['ws_step_enablews'] = 'Enabling site web services…';
$string['ws_step_registration_sent'] = 'Registration request sent.';
$string['ws_step_role_assign'] = 'Assigning role to service user…';
$string['ws_step_role_caps'] = 'Setting required role capabilities…';
$string['ws_step_role_create'] = 'Creating role "{$a}"…';
$string['ws_step_role_exists'] = 'Role already exists, using ID {$a}…';
$string['ws_step_service_enable'] = 'Creating/Enabling external service…';
$string['ws_step_service_functions'] = 'Adding common core functions to the service…';
$string['ws_step_service_user'] = 'Authorising user for the service…';
$string['ws_step_setup'] = 'Starting setup…';
$string['ws_step_token_create'] = 'Ensuring token exists…';
$string['ws_step_token_regenerated'] = 'Token regenerated.';
$string['ws_step_token_regenerating'] = 'Regenerating token…';
$string['ws_step_token_retry'] = 'Retrying setup…';
$string['ws_step_user_check'] = 'Verifying if user "{$a}" exists…';
$string['ws_step_user_create'] = 'Creating service user "{$a}"…';
$string['ws_tokenexists'] = 'Token exists';
$string['ws_user'] = 'Service user';
$string['ws_userassigned'] = 'Role assigned to user';
