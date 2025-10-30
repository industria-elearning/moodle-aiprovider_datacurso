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
$string['action:generate_text:instruction_desc'] = 'Diese Anweisung wird zusammen mit der Benutzereingabe an das KI-Modell gesendet. Das Bearbeiten dieser Anweisung wird nicht empfohlen, es sei denn, es ist unbedingt erforderlich.';
$string['action:summarise_text:endpoint'] = 'API-Endpunkt';
$string['action:summarise_text:endpoint_desc'] = 'Der Endpunkt zum Generieren von Text';
$string['action:summarise_text:instruction'] = 'Systemanweisung';
$string['action:summarise_text:instruction_desc'] = 'Diese Anweisung wird zusammen mit der Benutzereingabe an das KI-Modell gesendet. Das Bearbeiten dieser Anweisung wird nicht empfohlen, es sei denn, es ist unbedingt erforderlich.';
$string['all'] = 'Alle';
$string['apikey'] = 'API-Schlüssel';
$string['apikey_desc'] = 'Geben Sie den API-Schlüssel Ihres Datacurso-Dienstes ein, um die KI zu verbinden.';
$string['apiurl'] = 'Basis-API-URL';
$string['apiurl_desc'] = 'Geben Sie die Basis-URL des Dienstes ein, um eine Verbindung zur Datacurso-API herzustellen.';
$string['assigned'] = 'Zugewiesen';
$string['chart_actions'] = 'Kreditverteilung nach Service';
$string['chart_tokens_by_day'] = 'Kreditverbrauch nach Tag';
$string['chart_tokens_by_month'] = 'Anzahl der pro Monat verbrauchten Kredite';
$string['configured'] = 'Konfiguriert';
$string['contextwstoken'] = 'Webservice-Token für Kurskontext';
$string['contextwstoken_desc'] = 'Token, das von der KI verwendet wird, um Kursinformationen (Kontext) abzurufen. Sicher gespeichert. Tokens erstellen/verwalten unter Website-Administration > Server > Webdienste > Tokens verwalten.';
$string['created'] = 'Erstellt';
$string['curlerror'] = 'Datacurso API cURL-Fehler: {$a}';
$string['datacurso:manage'] = 'KI-Anbieter-Einstellungen verwalten';
$string['datacurso:use'] = 'Datacurso KI-Dienste nutzen';
$string['datacurso:viewreports'] = 'KI-Nutzungsberichte anzeigen';
$string['description'] = 'Beschreibung';
$string['descriptionpagelistplugins'] = 'Hier finden Sie die Liste der Plugins, die mit dem Datacurso-Anbieter kompatibel sind';
$string['disabled'] = 'Deaktiviert';
$string['emptyprompt'] = 'Leere Eingabeaufforderung';
$string['emptyresponse'] = 'Keine Antwort von der Datacurso-API.';
$string['enabled'] = 'Aktiviert';
$string['enableglobalratelimit'] = 'Globales Limit aktivieren';
$string['enableglobalratelimit_desc'] = 'Wenn aktiviert, wird ein globales Anfragelimit pro Stunde für alle Benutzer angewendet.';
$string['enableuserratelimit'] = 'Benutzerlimit aktivieren';
$string['enableuserratelimit_desc'] = 'Wenn aktiviert, hat jeder Benutzer ein stündliches Anfragelimit.';
$string['errorgetbalancecredits'] = 'Das Kreditguthaben konnte nicht von der externen API abgerufen werden';
$string['errorinitinformation'] = 'Anfangsinformationen konnten nicht abgerufen werden.';
$string['exists'] = 'Existiert';
$string['forbidden'] = 'Sie dürfen diese Aktion mit der aktuellen Lizenz nicht ausführen. Bitte überprüfen Sie Ihre Lizenz und verfügbaren Kredite unter <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Kredite verwalten</a> im Datacurso-Shop.';
$string['generate_activitie'] = 'Aktivität oder Ressource mit KI generieren';
$string['generate_ai_reinforcement_activity'] = 'KI-Verstärkungsaktivität erstellen';
$string['generate_analysis_comments'] = 'Bewertungsanalyse einer Aktivität/Ressource mit KI generieren';
$string['generate_analysis_course'] = 'Kursbewertungsanalyse mit KI generieren';
$string['generate_analysis_general'] = 'Allgemeine Bewertungsanalyse mit KI generieren';
$string['generate_analysis_story_student'] = 'Analysebericht des Studenten mit KI generieren';
$string['generate_assign_answer'] = 'Aufgabenbewertung mit KI generieren';
$string['generate_certificate_answer'] = 'Zertifikatsnachricht mit KI generieren';
$string['generate_creation_course'] = 'Vollständigen Kurs mit KI erstellen';
$string['generate_forum_chat'] = 'Forumantwort mit KI generieren';
$string['generate_image'] = 'Bild mit KI generieren';
$string['generate_plan_course'] = 'Kurserstellungsplan mit KI generieren';
$string['generate_summary'] = 'Zusammenfassung mit KI generieren';
$string['generate_text'] = 'Text mit KI generieren';
$string['globalratelimit'] = 'Globales Anfragelimit';
$string['globalratelimit_desc'] = 'Maximale Anzahl von Anfragen pro Stunde für das gesamte System.';
$string['goto'] = 'Zum Bericht gehen';
$string['gotopage'] = 'Zur Seite gehen';
$string['httperror'] = 'Unerwarteter Fehler bei der Verarbeitung Ihrer Anfrage (HTTP {$a}). Bitte versuchen Sie es später erneut. Wenn das Problem weiterhin besteht, wenden Sie sich an Ihren Website-Administrator.';
$string['id'] = 'ID';
$string['installed'] = 'Installiert';
$string['invalidlicensekey'] = 'Der Lizenzschlüssel ist abgelaufen oder ungültig. Bitte gehen Sie zu <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Kredite verwalten</a> im Datacurso-Shop, um Ihre Lizenz zu erneuern oder eine neue zu erwerben.';
$string['json_encode_failed'] = 'JSON-Codierung fehlgeschlagen';
$string['jsondecodeerror'] = 'Fehler beim Verarbeiten der Antwort von der Datacurso-API: {$a}';
$string['last_sent'] = 'Zuletzt gesendet';
$string['license_not_allowed'] = 'Ihre Lizenz erlaubt diese Anfrage nicht. Bitte verwalten Sie Ihre Lizenzen und Kredite unter <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Kredite verwalten</a> im Datacurso-Shop.';
$string['licensekey'] = 'Lizenzschlüssel';
$string['licensekey_desc'] = 'Geben Sie den Lizenzschlüssel aus dem Datacurso-Shop-Kundenbereich ein.';
$string['link_consumptionhistory'] = 'Kreditverbrauchsverlauf';
$string['link_generalreport'] = 'Allgemeiner Bericht';
$string['link_generalreport_datacurso'] = 'Allgemeiner Bericht Datacurso AI';
$string['link_listplugings'] = 'Liste der Datacurso-Plugins';
$string['link_plugin'] = 'Link';
$string['link_report_statistic'] = 'Allgemeiner Statistikbericht';
$string['link_webservice_config'] = 'Datacurso-Webservice-Einrichtung';
$string['live_log'] = 'Live-Protokoll';
$string['message_no_there_plugins'] = 'Keine Plugins verfügbar';
$string['missing'] = 'Fehlt';
$string['needs_repair'] = 'Benötigt Reparatur';
$string['nodata'] = 'Keine Informationen gefunden';
$string['not_assigned'] = 'Nicht zugewiesen';
$string['not_configured'] = 'Nicht konfiguriert';
$string['not_created'] = 'Nicht erstellt';
$string['notenoughtokens'] = 'Unzureichende KI-Kredite. Bitte besuchen Sie <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Kredite verwalten</a> im Datacurso-Shop, um mehr Kredite zuzuweisen oder zu erwerben. Oder wenden Sie sich an Ihren Administrator.';
$string['of'] = 'von';
$string['orgid'] = 'Organisations-ID';
$string['orgid_desc'] = 'Geben Sie die Kennung Ihrer Organisation im Datacurso-Dienst ein.';
$string['pageinfo'] = 'Seite {$a->current} von {$a->totalpages} ({$a->total} Einträge)';
$string['pending'] = 'Ausstehend';
$string['plugin'] = 'Plugin';
$string['plugindesc_assign_ai'] = 'Aufgaben mit KI-Unterstützung bewerten.';
$string['plugindesc_coursegen'] = 'Vollständige Kurse, Aktivitäten und Ressourcen mit KI erstellen.';
$string['plugindesc_datacurso_ratings'] = 'Ermöglicht Studierenden, Aktivitäten und Ressourcen zu bewerten; Lehrende und Administratoren können später KI-basierte Kursanalysen erstellen.';
$string['plugindesc_dttutor'] = 'Mit einem KI-Tutor im Kurs chatten.';
$string['plugindesc_forum_ai'] = 'Foren mit KI-gestützter Analyse erweitern, um automatisch Zusammenfassungen zu generieren.';
$string['plugindesc_lifestory'] = 'KI-gestützter Bericht und Analyse des akademischen Fortschritts des Studierenden.';
$string['plugindesc_smartrules'] = 'Automatisierte Aktivitäten basierend auf früheren Bedingungen der Studierenden erstellen.';
$string['plugindesc_socialcert'] = 'Personalisierte Zertifikate bei Kursabschluss automatisch generieren.';
$string['pluginname'] = 'Datacurso KI-Anbieter';
$string['pluginname_assign_ai'] = 'Aufgaben-KI';
$string['pluginname_coursegen'] = 'Kurserstellungs-KI';
$string['pluginname_datacurso_ratings'] = 'Aktivitätsbewertungs-KI';
$string['pluginname_dttutor'] = 'Tutor-KI';
$string['pluginname_forum_ai'] = 'Forum-KI';
$string['pluginname_lifestory'] = 'Studenten-Lebensgeschichte-KI';
$string['pluginname_smartrules'] = 'SmartRules-KI';
$string['pluginname_socialcert'] = 'Zertifikat-teilen-KI';
$string['privacy:metadata'] = 'Das Datacurso KI-Anbieter-Plugin speichert keine personenbezogenen Daten lokal. Alle Daten werden von externen Datacurso-KI-Diensten verarbeitet.';
$string['privacy:metadata:aiprovider_datacurso'] = 'Datacurso KI-Anfrage-Nutzdaten, die an den externen Dienst gesendet werden.';
$string['privacy:metadata:aiprovider_datacurso:externalpurpose'] = 'Diese Daten werden an Datacurso KI gesendet, um die angeforderte Aktion auszuführen.';
$string['privacy:metadata:aiprovider_datacurso:numberimages'] = 'Gesamtzahl der vom KI-Dienst angeforderten Bilder.';
$string['privacy:metadata:aiprovider_datacurso:prompt'] = 'Der an den KI-Dienst übermittelte Eingabetext.';
$string['privacy:metadata:aiprovider_datacurso:userid'] = 'Die Moodle-Benutzer-ID, die die KI-Anfrage stellt.';
$string['read_context_course'] = 'Kontext für KI-Kurserstellung lesen';
$string['read_context_course_model'] = 'Akademisches Modell für KI-Kurserstellung hochladen';
$string['registers'] = 'Einträge';
$string['registration_error'] = 'Letzter Fehler';
$string['registration_last'] = 'Registrierung';
$string['registration_lastsent'] = 'Zuletzt gesendet';
$string['registration_notverified'] = 'Registrierung nicht verifiziert';
$string['registration_status'] = 'Letzter Status';
$string['registration_verified'] = 'Registrierung verifiziert';
$string['registrationapibearer'] = 'Registrierungs-Bearer-Token';
$string['registrationapibearer_desc'] = 'Bearer-Token zur Authentifizierung der Registrierungsanfrage.';
$string['registrationapiurl'] = 'Registrierungs-Endpunkt-URL';
$string['registrationapiurl_desc'] = 'Endpunkt zum Empfangen der Website-Registrierungsnutzdaten. Standard: http://localhost:8001/register-site';
$string['registrationsettings'] = 'Registrierungs-API';
$string['remainingtokens'] = 'Verbleibendes Guthaben';
$string['responseinvalidai'] = 'Ungültige Antwort vom KI-Dienst.';
$string['responseinvalidaimage'] = 'Ungültige Antwort vom KI-Dienst (kein Bild).';
$string['responseinvalidaimagecreate'] = 'Bilddatei konnte nicht erstellt werden.';
$string['rest_enabled'] = 'REST-Protokoll aktiviert';
$string['service'] = 'Dienst';
$string['showrows'] = 'Zeilen anzeigen';
$string['tokens'] = 'Kredite';
$string['tokens_available'] = 'Verfügbare Kredite';
$string['tokensconsumed'] = 'Verbrauchte Kredite';
$string['tokensconsumedday'] = 'Pro Tag verbrauchte Kredite';
$string['tokensconsumedmonth'] = 'Pro Monat verbrauchte Kredite';
$string['tokensnotsufficient'] = 'Unzureichende KI-Kredite. Aktuelles Guthaben: {$a->current}. Mindestens erforderlich: {$a->required}. Bitte besuchen Sie <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Kredite verwalten</a> im Datacurso-Shop, um mehr Kredite zuzuweisen oder zu erwerben. Oder wenden Sie sich an Ihren Administrator.';
$string['tokensused'] = 'Verwendete Kredite';
$string['tokenthreshold'] = 'Kreditschwellenwert';
$string['tokenthreshold_desc'] = 'Anzahl der Kredite, ab der eine Benachrichtigung zum Kauf weiterer Kredite angezeigt wird.';
$string['total_consumed'] = 'Verbrauchte Kredite';
$string['userid'] = 'Benutzer';
$string['userratelimit'] = 'Benutzerspezifisches Anfragelimit';
$string['userratelimit_desc'] = 'Maximale Anzahl von Anfragen pro Stunde für jeden einzelnen Benutzer.';
$string['verified'] = 'Verifiziert';
$string['webserviceconfig_current'] = 'Aktuelle Konfiguration';
$string['webserviceconfig_desc'] = 'Konfiguriert automatisch einen dedizierten Webservice für den Datacurso-KI-Dienst, damit dieser sicher Plattforminformationen wie grundlegende Benutzerdaten, Kurse und Aktivitäten für eine bessere KI-Kontextualisierung extrahieren kann. Diese Einrichtung erstellt einen Dienstbenutzer, weist die erforderliche Rolle zu, konfiguriert den externen Dienst, generiert ein sicheres Token und aktiviert das REST-Protokoll mit einem Klick. Hinweis: Der Token-Wert wird aus Sicherheitsgründen nicht angezeigt.';
$string['webserviceconfig_heading'] = 'Automatische Webservice-Einrichtung';
$string['webserviceconfig_site'] = 'Website-Informationen';
$string['webserviceconfig_status'] = 'Status';
$string['webserviceconfig_title'] = 'Datacurso-Webservices-Konfiguration';
$string['workplace'] = 'Ist dies Moodle Workplace?';
$string['workplace_desc'] = 'Definiert, ob der X-Workplace-Header mit dem Wert true (Workplace) oder false (Standard-Moodle) gesendet werden soll.';
$string['ws_activity'] = 'Aktivitätsprotokoll';
$string['ws_btn_regenerate'] = 'Token neu generieren';
$string['ws_btn_retry'] = 'Konfiguration wiederholen';
$string['ws_btn_setup'] = 'Webservice konfigurieren';
$string['ws_enabled'] = 'Webdienste aktiviert';
$string['ws_error_missing_setup'] = 'Dienst oder Benutzer nicht gefunden. Führen Sie zuerst die Einrichtung durch.';
$string['ws_error_missing_token'] = 'Token nicht gefunden. Generieren Sie es zuerst.';
$string['ws_error_regenerate_token'] = 'Fehler beim Neugenerieren des Tokens.';
$string['ws_error_registration'] = 'Fehler beim Registrieren des Webservice-Tokens.';
$string['ws_error_setup'] = 'Fehler beim Konfigurieren des Webservice.';
$string['ws_role'] = 'Dienstrolle';
$string['ws_service'] = 'Externer Dienst';
$string['ws_step_enableauth'] = 'Webservices-Auth-Plugin wird aktiviert…';
$string['ws_step_enablerest'] = 'REST-Protokoll wird aktiviert…';
$string['ws_step_enablews'] = 'Website-Webdienste werden aktiviert…';
$string['ws_step_registration_sent'] = 'Registrierungsanfrage gesendet.';
$string['ws_step_role_assign'] = 'Rolle wird Dienstbenutzer zugewiesen…';
$string['ws_step_role_caps'] = 'Erforderliche Rollenberechtigungen werden festgelegt…';
$string['ws_step_role_create'] = 'Rolle "{$a}" wird erstellt…';
$string['ws_step_role_exists'] = 'Rolle existiert bereits, verwende ID {$a}…';
$string['ws_step_service_enable'] = 'Externer Dienst wird erstellt/aktiviert…';
$string['ws_step_service_functions'] = 'Allgemeine Core-Funktionen werden zum Dienst hinzugefügt…';
$string['ws_step_service_user'] = 'Benutzer wird für den Dienst autorisiert…';
$string['ws_step_setup'] = 'Einrichtung wird gestartet…';
$string['ws_step_token_create'] = 'Token-Existenz wird sichergestellt…';
$string['ws_step_token_generated'] = 'Token generiert.';
$string['ws_step_token_regenerated'] = 'Token neu generiert.';
$string['ws_step_token_regenerating'] = 'Token wird neu generiert…';
$string['ws_step_token_retry'] = 'Einrichtung wird wiederholt…';
$string['ws_step_user_check'] = 'Überprüfung, ob Benutzer "{$a}" existiert…';
$string['ws_step_user_create'] = 'Dienstbenutzer "{$a}" wird erstellt…';
$string['ws_tokenexists'] = 'Token existiert';
$string['ws_user'] = 'Dienstbenutzer';
$string['ws_userassigned'] = 'Rolle dem Benutzer zugewiesen';
