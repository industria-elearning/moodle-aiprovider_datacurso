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

$string['action'] = 'Aktion';
$string['action:generate_image:endpoint'] = 'API-Endpunkt';
$string['action:generate_image:endpoint_desc'] = 'Der Endpunkt zum Generieren von Bildern';
$string['action:generate_text:endpoint'] = 'API-Endpunkt';
$string['action:generate_text:endpoint_desc'] = 'Der Endpunkt zum Generieren von Text';
$string['action:generate_text:instruction'] = 'Systemanweisung';
$string['action:generate_text:instruction_desc'] = 'Diese Anweisung wird zusammen mit der Eingabeaufforderung des Benutzers an das KI-Modell gesendet. Das Bearbeiten dieser Anweisung wird nicht empfohlen, es sei denn, es ist unbedingt erforderlich.';
$string['action:summarise_text:endpoint'] = 'API-Endpunkt';
$string['action:summarise_text:endpoint_desc'] = 'Der Endpunkt zum Generieren von Text';
$string['action:summarise_text:instruction'] = 'Systemanweisung';
$string['action:summarise_text:instruction_desc'] = 'Diese Anweisung wird zusammen mit der Eingabeaufforderung des Benutzers an das KI-Modell gesendet. Das Bearbeiten dieser Anweisung wird nicht empfohlen, es sei denn, es ist unbedingt erforderlich.';
$string['all'] = 'Alle';
$string['apikey'] = 'API-Schlüssel';
$string['apikey_desc'] = 'Geben Sie den API-Schlüssel Ihres Datacurso-Dienstes ein, um die KI zu verbinden.';
$string['apiurl'] = 'Basis-API-URL';
$string['apiurl_desc'] = 'Geben Sie die Basis-URL des Dienstes ein, um eine Verbindung zur Datacurso-API herzustellen.';
$string['assigned'] = 'Zugewiesen';
$string['chart_actions'] = 'Token-Verteilung nach Dienst';
$string['chart_tokens_by_day'] = 'Token-Verbrauch pro Tag';
$string['chart_tokens_by_month'] = 'Anzahl der pro Monat verbrauchten Token';
$string['configured'] = 'Konfiguriert';
$string['contextwstoken'] = 'Webservice-Token für Kurskontext';
$string['contextwstoken_desc'] = 'Token, das von der KI zum Abrufen von Kursinformationen (Kontext) verwendet wird. Sicher gespeichert. Token erstellen/verwalten unter Website-Administration > Server > Webservices > Token verwalten.';
$string['created'] = 'Erstellt';
$string['datacurso:manage'] = 'KI-Anbieter-Einstellungen verwalten';
$string['datacurso:use'] = 'Datacurso KI-Dienste nutzen';
$string['datacurso:viewreports'] = 'KI-Nutzungsberichte anzeigen';
$string['description'] = 'Beschreibung';
$string['descriptionpagelistplugins'] = 'Hier finden Sie die Liste der mit dem Datacurso-Anbieter kompatiblen Plugins';
$string['disabled'] = 'Deaktiviert';
$string['enabled'] = 'Aktiviert';
$string['enableglobalratelimit'] = 'Globales Limit aktivieren';
$string['enableglobalratelimit_desc'] = 'Wenn aktiviert, wird ein globales Anfragelimit pro Stunde für alle Benutzer angewendet.';
$string['enableuserratelimit'] = 'Benutzerlimit aktivieren';
$string['enableuserratelimit_desc'] = 'Wenn aktiviert, hat jeder Benutzer ein stündliches Anfragelimit.';
$string['exists'] = 'Existiert';
$string['generate_activitie'] = 'Aktivität oder Ressource mit KI generieren';
$string['generate_analysis_comments'] = 'Bewertungsanalyse einer Aktivität/Ressource mit KI generieren';
$string['generate_analysis_course'] = 'Kursbewertungsanalyse mit KI generieren';
$string['generate_analysis_general'] = 'Allgemeine Bewertungsanalyse mit KI generieren';
$string['generate_analysis_story_student'] = 'Analyse der Studierendendaten mit KI generieren';
$string['generate_assign_answer'] = 'Aufgabenbewertung mit KI generieren';
$string['generate_certificate_answer'] = 'Zertifikatsnachricht mit KI generieren';
$string['generate_creation_course'] = 'Vollständigen Kurs mit KI erstellen';
$string['generate_forum_chat'] = 'Forumsantwort mit KI generieren';
$string['generate_image'] = 'Bild mit KI generieren';
$string['generate_plan_course'] = 'Kurserstellungsplan mit KI generieren';
$string['generate_summary'] = 'Zusammenfassung mit KI generieren';
$string['generate_text'] = 'Text mit KI generieren';
$string['globalratelimit'] = 'Globales Anfragelimit';
$string['globalratelimit_desc'] = 'Maximale Anzahl der pro Stunde für das gesamte System zulässigen Anfragen.';
$string['goto'] = 'Zum Bericht gehen';
$string['gotopage'] = 'Zur Seite gehen';
$string['id'] = 'ID';
$string['installed'] = 'Installiert';
$string['invalidlicensekey'] = 'Ungültiger Lizenzschlüssel';
$string['last_sent'] = 'Zuletzt gesendet';
$string['licensekey'] = 'Lizenzschlüssel';
$string['licensekey_desc'] = 'Geben Sie den bereitgestellten Lizenzschlüssel ein (Beispiel: LICENSE_KEY_CLIENT).';
$string['link_consumptionhistory'] = 'Token-Verbrauchsverlauf';
$string['link_generalreport'] = 'Allgemeiner Bericht';
$string['link_generalreport_datacurso'] = 'Allgemeiner Bericht Datacurso AI';
$string['link_listplugings'] = 'Liste der Datacurso-Plugins';
$string['link_plugin'] = 'Link';
$string['link_report_statistic'] = 'Allgemeiner Statistikbericht';
$string['link_webservice_config'] = 'Datacurso Webservice-Konfiguration';
$string['live_log'] = 'Live-Protokoll';
$string['message_no_there_plugins'] = 'Keine Plugins verfügbar';
$string['missing'] = 'Fehlend';
$string['needs_repair'] = 'Reparatur erforderlich';
$string['nodata'] = 'Keine Informationen gefunden';
$string['not_assigned'] = 'Nicht zugewiesen';
$string['not_configured'] = 'Nicht konfiguriert';
$string['not_created'] = 'Nicht erstellt';
$string['orgid'] = 'Organisations-ID';
$string['orgid_desc'] = 'Geben Sie die Kennung Ihrer Organisation im Datacurso-Dienst ein.';
$string['pending'] = 'Ausstehend';
$string['plugin'] = 'Plugin';
$string['pluginname'] = 'Datacurso-Anbieter';
$string['privacy:metadata'] = 'Das Datacurso AI Provider Plugin speichert keine personenbezogenen Daten lokal. Alle Daten werden von externen Datacurso AI-Diensten verarbeitet.';
$string['privacy:metadata:datacurso_ai_services'] = 'Daten, die zur Verarbeitung an Datacurso AI-Dienste gesendet werden.';
$string['privacy:metadata:datacurso_ai_services:request_data'] = 'Die zur Verarbeitung an den KI-Dienst gesendeten Daten.';
$string['privacy:metadata:datacurso_ai_services:response_data'] = 'Die vom KI-Dienst empfangene Antwort.';
$string['privacy:metadata:datacurso_ai_services:timestamp'] = 'Zeitpunkt der KI-Anfrage.';
$string['privacy:metadata:datacurso_ai_services:tokens_consumed'] = 'Anzahl der bei der Anfrage verbrauchten Token.';
$string['privacy:metadata:datacurso_ai_services:userid'] = 'Die Benutzer-ID, die die KI-Anfrage stellt.';
$string['read_context_course'] = 'Kontext für KI-Kurserstellung lesen';
$string['read_context_course_model'] = 'Akademisches Modell für KI-Kurserstellung hochladen';
$string['registration_error'] = 'Letzter Fehler';
$string['registration_last'] = 'Registrierung';
$string['registration_lastsent'] = 'Zuletzt gesendet';
$string['registration_notverified'] = 'Registrierung nicht verifiziert';
$string['registration_status'] = 'Letzter Status';
$string['registration_verified'] = 'Registrierung verifiziert';
$string['registrationapibearer'] = 'Registrierungs-Bearer-Token';
$string['registrationapibearer_desc'] = 'Bearer-Token zur Authentifizierung der Registrierungsanfrage.';
$string['registrationapiurl'] = 'Registrierungs-Endpunkt-URL';
$string['registrationapiurl_desc'] = 'Endpunkt zum Empfangen der Website-Registrierungsdaten. Standard: http://localhost:8001/register-site';
$string['registrationsettings'] = 'Registrierungs-API';
$string['remainingtokens'] = 'Verbleibendes Guthaben';
$string['rest_enabled'] = 'REST-Protokoll aktiviert';
$string['service'] = 'Dienst';
$string['showrows'] = 'Zeilen anzeigen';
$string['tokens_available'] = 'Verfügbare Token';
$string['tokensused'] = 'Verwendete Token';
$string['tokenthreshold'] = 'Token-Schwellenwert';
$string['tokenthreshold_desc'] = 'Anzahl der Token, ab der eine Benachrichtigung zum Kauf weiterer Token angezeigt wird.';
$string['total_consumed'] = 'Insgesamt verbraucht';
$string['userid'] = 'Benutzer';
$string['userratelimit'] = 'Anfragelimit pro Benutzer';
$string['userratelimit_desc'] = 'Maximale Anzahl der pro Stunde für jeden einzelnen Benutzer zulässigen Anfragen.';
$string['verified'] = 'Verifiziert';
$string['webserviceconfig_current'] = 'Aktuelle Konfiguration';
$string['webserviceconfig_desc'] = 'Konfiguriert automatisch einen dedizierten Webservice für den Datacurso AI-Dienst, der es ihm ermöglicht, Plattforminformationen wie Benutzerdaten, Kurse und Aktivitäten sicher zu extrahieren, um eine bessere KI-Kontextualisierung zu ermöglichen. Diese Einrichtung erstellt einen Dienstbenutzer, weist die erforderliche Rolle zu, konfiguriert den externen Dienst, generiert ein sicheres Token und aktiviert das REST-Protokoll mit einem Klick. Hinweis: Der Token-Wert wird aus Sicherheitsgründen nicht angezeigt.';
$string['webserviceconfig_heading'] = 'Automatische Webservice-Konfiguration';
$string['webserviceconfig_site'] = 'Website-Informationen';
$string['webserviceconfig_status'] = 'Status';
$string['webserviceconfig_title'] = 'Datacurso Webservice-Konfiguration';
$string['workplace'] = 'Ist dies Moodle Workplace?';
$string['workplace_desc'] = 'Definiert, ob der X-Workplace-Header mit dem Wert true (Workplace) oder false (Standard-Moodle) gesendet werden soll.';
$string['ws_activity'] = 'Aktivitätsprotokoll';
$string['ws_btn_regenerate'] = 'Token regenerieren';
$string['ws_btn_retry'] = 'Konfiguration wiederholen';
$string['ws_btn_setup'] = 'Webservice konfigurieren';
$string['ws_enabled'] = 'Webservices aktiviert';
$string['ws_error_missing_setup'] = 'Dienst oder Benutzer nicht gefunden. Führen Sie zuerst die Einrichtung durch.';
$string['ws_error_missing_token'] = 'Token nicht gefunden. Generieren Sie es zuerst.';
$string['ws_error_regenerate_token'] = 'Fehler beim Regenerieren des Tokens.';
$string['ws_error_registration'] = 'Fehler beim Registrieren des Webservice-Tokens.';
$string['ws_error_setup'] = 'Fehler beim Konfigurieren des Webservices.';
$string['ws_role'] = 'Dienst-Rolle';
$string['ws_service'] = 'Externer Dienst';
$string['ws_step_enableauth'] = 'Webservices-Authentifizierungs-Plugin wird aktiviert…';
$string['ws_step_enablerest'] = 'REST-Protokoll wird aktiviert…';
$string['ws_step_enablews'] = 'Website-Webservices werden aktiviert…';
$string['ws_step_registration_sent'] = 'Registrierungsanfrage gesendet.';
$string['ws_step_role_assign'] = 'Rolle wird Dienstbenutzer zugewiesen…';
$string['ws_step_role_caps'] = 'Erforderliche Rollenfähigkeiten werden festgelegt…';
$string['ws_step_role_create'] = 'Rolle "{$a}" wird erstellt…';
$string['ws_step_role_exists'] = 'Rolle existiert bereits, verwende ID {$a}…';
$string['ws_step_service_enable'] = 'Externer Dienst wird erstellt/aktiviert…';
$string['ws_step_service_functions'] = 'Allgemeine Kernfunktionen werden dem Dienst hinzugefügt…';
$string['ws_step_service_user'] = 'Benutzer wird für den Dienst autorisiert…';
$string['ws_step_setup'] = 'Einrichtung wird gestartet…';
$string['ws_step_token_create'] = 'Sicherstellung, dass Token existiert…';
$string['ws_step_token_generated'] = 'Token generiert.';
$string['ws_step_token_regenerated'] = 'Token regeneriert.';
$string['ws_step_token_regenerating'] = 'Token wird regeneriert…';
$string['ws_step_token_retry'] = 'Einrichtung wird wiederholt…';
$string['ws_step_user_check'] = 'Überprüfung, ob Benutzer "{$a}" existiert…';
$string['ws_step_user_create'] = 'Dienstbenutzer "{$a}" wird erstellt…';
$string['ws_tokenexists'] = 'Token existiert';
$string['ws_user'] = 'Dienstbenutzer';
$string['ws_userassigned'] = 'Rolle dem Benutzer zugewiesen';
