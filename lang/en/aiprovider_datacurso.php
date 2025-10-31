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
$string['action:generate_image:endpoint'] = 'API endpoint';
$string['action:generate_image:endpoint_desc'] = 'There endpoint the generate image';
$string['action:generate_text:endpoint'] = 'API endpoint';
$string['action:generate_text:endpoint_desc'] = 'There endpoint the generate text';
$string['action:generate_text:instruction'] = 'System instruction';
$string['action:generate_text:instruction_desc'] = 'This instruction is sent to the AI model along with the user\'s prompt. Editing this instruction is not recommended unless absolutely required.';
$string['action:summarise_text:endpoint'] = 'API endpoint';
$string['action:summarise_text:endpoint_desc'] = 'There endpoint the generate text';
$string['action:summarise_text:instruction'] = 'System instruction';
$string['action:summarise_text:instruction_desc'] = 'This instruction is sent to the AI model along with the user\'s prompt. Editing this instruction is not recommended unless absolutely required.';
$string['all'] = 'All';
$string['alt_datacurso_icon'] = 'Datacurso icon';
$string['apikey'] = 'API key';
$string['apikey_desc'] = 'Enter the API key from your Datacurso service to connect the AI.';
$string['apiurl'] = 'Base API URL';
$string['apiurl_desc'] = 'Enter the base URL of the service to connect to the Datacurso API.';
$string['assigned'] = 'Assigned';
$string['chart_actions'] = 'Credits distribution by service';
$string['chart_tokens_by_day'] = 'Credits consumption by day';
$string['chart_tokens_by_month'] = 'Number of credits consumed per month';
$string['configured'] = 'Configured';
$string['contextwstoken'] = 'Web service token for course context';
$string['contextwstoken_desc'] = 'Token used by the AI to retrieve course information (context). Stored securely. Create/manage tokens in Site administration > Server > Web services > Manage tokens.';
$string['created'] = 'Created';
$string['curlerror'] = 'Datacurso API cURL error: {$a}';
$string['datacurso:manage'] = 'Manage AI provider settings';
$string['datacurso:use'] = 'Use Datacurso AI services';
$string['datacurso:viewreports'] = 'View AI usage reports';
$string['day'] = 'day';
$string['days'] = 'Days';
$string['description'] = 'Description';
$string['descriptionpagelistplugins'] = 'Here you can find the list of plugins compatible with the Datacurso provider';
$string['disabled'] = 'Disabled';
$string['emptyprompt'] = 'Empty prompt';
$string['emptyresponse'] = 'No response from Datacurso API.';
$string['enabled'] = 'Enabled';
$string['enableglobalratelimit'] = 'Enable global limit';
$string['enableglobalratelimit_desc'] = 'If enabled, a global request limit per hour will be applied for all users.';
$string['enableuserratelimit'] = 'Enable per-user limit';
$string['enableuserratelimit_desc'] = 'If enabled, each user will have an hourly request limit.';
$string['error_ratelimit_exceeded'] = 'Rate limit exceeded. Please try again later.';
$string['errorgetbalancecredits'] = 'Could not retrieve credits balance from external API';
$string['errorinitinformation'] = 'Initial information could not be obtained.';
$string['exists'] = 'Exists';
$string['forbidden'] = 'You are not allowed to perform this action with the current license. Please verify your license and available credits in <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Manage Credits</a> in the Datacurso Shop.';
$string['generate_activitie'] = 'Generate activity or resource with AI';
$string['generate_ai_reinforcement_activity'] = 'Create AI reinforcement activity';
$string['generate_analysis_comments'] = 'Generate rating analysis of an activity/resource with AI';
$string['generate_analysis_course'] = 'Generate course rating analysis with AI';
$string['generate_analysis_general'] = 'Generate general rating analysis with AI';
$string['generate_analysis_story_student'] = 'Generate analysis story student with AI';
$string['generate_assign_answer'] = 'Generate assignment review with AI';
$string['generate_certificate_answer'] = 'Generate certificate message with AI';
$string['generate_creation_course'] = 'Create complete course with AI';
$string['generate_forum_chat'] = 'Generate forum response with AI';
$string['generate_image'] = 'Generate image with AI';
$string['generate_plan_course'] = 'Generate course creation plan with AI';
$string['generate_summary'] = 'Generate summary with AI';
$string['generate_text'] = 'Generate text with AI';
$string['globalratelimit'] = 'Global request limit';
$string['globalratelimit_desc'] = 'Maximum number of requests allowed per hour for the entire system.';
$string['goto'] = 'Go to Report';
$string['gotopage'] = 'Go to page';
$string['hour'] = 'hour';
$string['hours'] = 'Hours';
$string['httperror'] = 'Unexpected error while processing your request (HTTP {$a}). Please try again later. If the problem persists, contact your site administrator.';
$string['id'] = 'ID';
$string['installed'] = 'Installed';
$string['minute'] = 'minute';
$string['minutes'] = 'Minutes';
$string['second'] = 'second';
$string['seconds'] = 'Seconds';
$string['invalidlicensekey'] = 'License key has expired or is invalid. Please go to <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Manage Credits</a> in the Datacurso Shop to renew or purchase a new license.';
$string['json_encode_failed'] = 'Json Encode Failed';
$string['jsondecodeerror'] = 'Error processing response from Datacurso API: {$a}';
$string['last_sent'] = 'Last sent';
$string['license_not_allowed'] = 'Your license is not allowed to perform this request. Please manage your licenses and credits in <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Manage Credits</a> in the Datacurso Shop.';
$string['licensekey'] = 'License key';
$string['licensekey_desc'] = 'Enter the license key from the Datacurso Shop customer area.';
$string['link_consumptionhistory'] = 'Credits consumption history';
$string['link_generalreport'] = 'General report';
$string['link_generalreport_datacurso'] = 'General report Datacurso AI';
$string['link_listplugings'] = 'Datacurso plugins list';
$string['link_plugin'] = 'Link';
$string['link_report_statistic'] = 'General statistics report';
$string['link_webservice_config'] = 'Datacurso webservice setup';
$string['live_log'] = 'Live log';
$string['message_no_there_plugins'] = 'No plugins available';
$string['missing'] = 'Missing';
$string['month'] = 'month';
$string['months'] = 'Months';
$string['needs_repair'] = 'Needs repair';
$string['nodata'] = 'No information found';
$string['not_assigned'] = 'Not assigned';
$string['not_configured'] = 'Not configured';
$string['not_created'] = 'Not created';
$string['notenoughtokens'] = 'Insufficient AI credits. Please visit <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Manage Credits</a> in the Datacurso Shop to allocate or purchase more credits. Or contact your administrator.';
$string['of'] = 'of';
$string['orgid'] = 'Organization ID';
$string['orgid_desc'] = 'Enter the identifier of your organization in the Datacurso service.';
$string['pageinfo'] = 'Page {$a->current} of {$a->totalpages} ({$a->total} records)';
$string['pending'] = 'Pending';
$string['plugin'] = 'Plugin';
$string['plugindesc_assign_ai'] = 'Review assignments with AI assistance.';
$string['plugindesc_coursegen'] = 'Create complete courses, activities, and resources with AI.';
$string['plugindesc_datacurso_ratings'] = 'Allows students to rate activities and resources; teachers and administrators can later generate AI-based course analysis.';
$string['plugindesc_dttutor'] = 'Chat with an AI tutor within the course.';
$string['plugindesc_forum_ai'] = 'Extend forums with AI-powered analysis to automatically generate summaries.';
$string['plugindesc_lifestory'] = 'AI-powered report and analysis of the student’s academic progress.';
$string['plugindesc_smartrules'] = 'Create automated activities based on students’ previous conditions.';
$string['plugindesc_socialcert'] = 'Automatically generate personalized certificates upon course completion.';
$string['pluginname'] = 'Datacurso AI Provider';
$string['pluginname_assign_ai'] = 'Assign AI';
$string['pluginname_coursegen'] = 'Course Creator AI';
$string['pluginname_datacurso_ratings'] = 'Ranking Activities AI';
$string['pluginname_dttutor'] = 'Tutor AI';
$string['pluginname_forum_ai'] = 'Forum AI';
$string['pluginname_lifestory'] = 'Student Life Story AI';
$string['pluginname_smartrules'] = 'SmartRules AI';
$string['pluginname_socialcert'] = 'Share Certificate AI';
$string['privacy:metadata'] = 'The Datacurso AI Provider plugin does not store any personal data locally. All data is processed by external Datacurso AI services.';
$string['privacy:metadata:aiprovider_datacurso'] = 'Datacurso AI request payloads sent to the external service.';
$string['privacy:metadata:aiprovider_datacurso:externalpurpose'] = 'This data is sent to Datacurso AI in order to fulfil the requested action.';
$string['privacy:metadata:aiprovider_datacurso:numberimages'] = 'Total number of images requested from the AI service.';
$string['privacy:metadata:aiprovider_datacurso:prompt'] = 'The prompt text supplied to the AI service.';
$string['privacy:metadata:aiprovider_datacurso:userid'] = 'The Moodle user ID making the AI request.';
$string['ratelimit_enable'] = 'Enable rate limit';
$string['ratelimit_enable_desc'] = 'If enabled, the per-user credit limit will be enforced for this plugin.';
$string['ratelimit_limit'] = 'Credit limit per window';
$string['ratelimit_limit_desc'] = 'Maximum number of credits a user can consume within the selected time window.';
$string['ratelimit_local_coursegen_activitycreators'] = 'Allowed activity creators';
$string['ratelimit_local_coursegen_activitycreators_desc'] = 'Select the users who can generate activities or resources with AI when this service is enabled.';
$string['ratelimit_local_coursegen_coursecreators'] = 'Allowed course creators';
$string['ratelimit_local_coursegen_coursecreators_desc'] = 'Select the users who can create complete courses with AI when this service is enabled.';
$string['ratelimit_window'] = 'Time window';
$string['ratelimit_window_desc'] = 'Select the duration and unit for the rate limit window.';
$string['ratelimits_heading'] = 'Per-plugin rate limits';
$string['ratelimits_heading_desc'] = 'Configure per-user rate limits per plugin that uses the Datacurso provider.';
$string['read_context_course'] = 'Read context for AI course creation';
$string['read_context_course_model'] = 'Upload academic model for AI course creation';
$string['registers'] = 'Registers';
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
$string['responseinvalidai'] = 'Invalid response from AI service.';
$string['responseinvalidaimage'] = 'Invalid response from AI service(No image).';
$string['responseinvalidaimagecreate'] = 'Could not create image file.';
$string['rest_enabled'] = 'REST protocol enabled';
$string['service'] = 'Service';
$string['showrows'] = 'Show rows';
$string['tokens'] = 'Credits';
$string['tokens_available'] = 'Available Credits';
$string['tokensconsumed'] = 'Credits consumed';
$string['tokensconsumedday'] = 'Credits consumed by day';
$string['tokensconsumedmonth'] = 'Credits consumed by month';
$string['tokensnotsufficient'] = 'Insufficient AI credits. Current balance: {$a->current}. Minimum required: {$a->required}. Please visit <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Manage Credits</a> in the Datacurso Shop to allocate or purchase more credits. Or contact your administrator.';
$string['tokensused'] = 'Credits used';
$string['tokenthreshold'] = 'Credits threshold';
$string['tokenthreshold_desc'] = 'Number of credits from which a notification will be shown to purchase more.';
$string['total_consumed'] = 'Credits consumed';
$string['userid'] = 'User';
$string['userratelimit'] = 'Per-user request limit';
$string['userratelimit_desc'] = 'Maximum number of requests allowed per hour for each individual user.';
$string['verified'] = 'Verified';
$string['webserviceconfig_current'] = 'Current configuration';
$string['webserviceconfig_desc'] = 'Automatically configures a dedicated webservice for the Datacurso AI service, enabling it to securely extract platform information such as user basic data, courses, and activities for better AI contextualization. This setup creates a service user, assigns the necessary role, configures the external service, generates a secure token, and enables the REST protocol in one click. Note: The token value is not displayed for security reasons.';
$string['webserviceconfig_heading'] = 'Automatic webservice setup';
$string['webserviceconfig_site'] = 'Site information';
$string['webserviceconfig_status'] = 'Status';
$string['webserviceconfig_title'] = 'Datacurso Web Service Configuration';
$string['week'] = 'week';
$string['weeks'] = 'Weeks';
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
$string['ws_role_desc'] = 'Role for Datacurso web service';
$string['ws_role_name'] = 'Datacurso web service';
$string['ws_service'] = 'External service';
$string['ws_service_name'] = 'Datacurso web service';
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
$string['ws_step_token_generated'] = 'Token generated.';
$string['ws_step_token_regenerated'] = 'Token regenerated.';
$string['ws_step_token_regenerating'] = 'Regenerating token…';
$string['ws_step_token_retry'] = 'Retrying setup…';
$string['ws_step_user_check'] = 'Verifying if user "{$a}" exists…';
$string['ws_step_user_create'] = 'Creating service user "{$a}"…';
$string['ws_token_label'] = 'Datacurso token';
$string['ws_tokenexists'] = 'Token exists';
$string['ws_user'] = 'Service user';
$string['ws_user_firstname'] = 'Datacurso';
$string['ws_user_lastname'] = 'Service';
$string['ws_userassigned'] = 'Role assigned to user';
$string['year'] = 'year';
$string['years'] = 'Years';
