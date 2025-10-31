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
$string['action:generate_image:endpoint'] = 'Point de terminaison API';
$string['action:generate_image:endpoint_desc'] = 'Le point de terminaison pour générer des images';
$string['action:generate_text:endpoint'] = 'Point de terminaison API';
$string['action:generate_text:endpoint_desc'] = 'Le point de terminaison pour générer du texte';
$string['action:generate_text:instruction'] = 'Instruction système';
$string['action:generate_text:instruction_desc'] = 'Cette instruction est envoyée au modèle IA avec la demande de l\'utilisateur. La modification de cette instruction n\'est pas recommandée sauf si absolument nécessaire.';
$string['action:summarise_text:endpoint'] = 'Point de terminaison API';
$string['action:summarise_text:endpoint_desc'] = 'Le point de terminaison pour générer du texte';
$string['action:summarise_text:instruction'] = 'Instruction système';
$string['action:summarise_text:instruction_desc'] = 'Cette instruction est envoyée au modèle IA avec la demande de l\'utilisateur. La modification de cette instruction n\'est pas recommandée sauf si absolument nécessaire.';
$string['all'] = 'Tous';
$string['apikey'] = 'Clé API';
$string['apikey_desc'] = 'Entrez la clé API de votre service Datacurso pour connecter l\'IA.';
$string['apiurl'] = 'URL de base de l\'API';
$string['apiurl_desc'] = 'Entrez l\'URL de base du service pour vous connecter à l\'API Datacurso.';
$string['assigned'] = 'Attribué';
$string['chart_actions'] = 'Distribution des crédits par service';
$string['chart_tokens_by_day'] = 'Consommation de crédits par jour';
$string['chart_tokens_by_month'] = 'Nombre de crédits consommés par mois';
$string['configured'] = 'Configuré';
$string['contextwstoken'] = 'Jeton de service web pour le contexte du cours';
$string['contextwstoken_desc'] = 'Jeton utilisé par l\'IA pour récupérer les informations du cours (contexte). Stocké de manière sécurisée. Créer/gérer les jetons dans Administration du site > Serveur > Services web > Gérer les jetons.';
$string['created'] = 'Créé';
$string['curlerror'] = 'Erreur cURL de l\'API Datacurso : {$a}';
$string['datacurso:manage'] = 'Gérer les paramètres du fournisseur IA';
$string['datacurso:use'] = 'Utiliser les services IA Datacurso';
$string['datacurso:viewreports'] = 'Voir les rapports d\'utilisation de l\'IA';
$string['description'] = 'Description';
$string['descriptionpagelistplugins'] = 'Vous trouverez ici la liste des plugins compatibles avec le fournisseur Datacurso';
$string['disabled'] = 'Désactivé';
$string['emptyprompt'] = 'Invite vide';
$string['emptyresponse'] = 'Aucune réponse de l\'API Datacurso.';
$string['enabled'] = 'Activé';
$string['enableglobalratelimit'] = 'Activer la limite globale';
$string['enableglobalratelimit_desc'] = 'Si activé, une limite de requêtes globale par heure sera appliquée pour tous les utilisateurs.';
$string['enableuserratelimit'] = 'Activer la limite par utilisateur';
$string['enableuserratelimit_desc'] = 'Si activé, chaque utilisateur aura une limite de requêtes par heure.';
$string['errorgetbalancecredits'] = 'Impossible de récupérer le solde de crédits depuis l\'API externe';
$string['errorinitinformation'] = 'Les informations initiales n\'ont pas pu être obtenues.';
$string['exists'] = 'Existe';
$string['forbidden'] = 'Vous n\'êtes pas autorisé à effectuer cette action avec la licence actuelle. Veuillez vérifier votre licence et les crédits disponibles dans <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gérer les crédits</a> dans la boutique Datacurso.';
$string['generate_activitie'] = 'Générer une activité ou une ressource avec l\'IA';
$string['generate_ai_reinforcement_activity'] = 'Créer une activité de renforcement IA';
$string['generate_analysis_comments'] = 'Générer une analyse de notation d\'une activité/ressource avec l\'IA';
$string['generate_analysis_course'] = 'Générer une analyse de notation du cours avec l\'IA';
$string['generate_analysis_general'] = 'Générer une analyse de notation générale avec l\'IA';
$string['generate_analysis_story_student'] = 'Générer une analyse de l\'histoire de l\'étudiant avec l\'IA';
$string['generate_assign_answer'] = 'Générer une révision de devoir avec l\'IA';
$string['generate_certificate_answer'] = 'Générer un message de certificat avec l\'IA';
$string['generate_creation_course'] = 'Créer un cours complet avec l\'IA';
$string['generate_forum_chat'] = 'Générer une réponse de forum avec l\'IA';
$string['generate_image'] = 'Générer une image avec l\'IA';
$string['generate_plan_course'] = 'Générer un plan de création de cours avec l\'IA';
$string['generate_summary'] = 'Générer un résumé avec l\'IA';
$string['generate_text'] = 'Générer du texte avec l\'IA';
$string['globalratelimit'] = 'Limite de requêtes globale';
$string['globalratelimit_desc'] = 'Nombre maximum de requêtes autorisées par heure pour l\'ensemble du système.';
$string['goto'] = 'Aller au rapport';
$string['gotopage'] = 'Aller à la page';
$string['httperror'] = 'Erreur inattendue lors du traitement de votre demande (HTTP {$a}). Veuillez réessayer plus tard. Si le problème persiste, contactez votre administrateur de site.';
$string['id'] = 'ID';
$string['installed'] = 'Installé';
$string['invalidlicensekey'] = 'La clé de licence a expiré ou n\'est pas valide. Veuillez aller sur <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gérer les crédits</a> dans la boutique Datacurso pour renouveler ou acheter une nouvelle licence.';
$string['json_encode_failed'] = 'Échec de l\'encodage JSON';
$string['jsondecodeerror'] = 'Erreur lors du traitement de la réponse de l\'API Datacurso : {$a}';
$string['last_sent'] = 'Dernier envoi';
$string['license_not_allowed'] = 'Votre licence ne permet pas d\'effectuer cette demande. Veuillez gérer vos licences et crédits dans <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gérer les crédits</a> dans la boutique Datacurso.';
$string['licensekey'] = 'Clé de licence';
$string['licensekey_desc'] = 'Entrez la clé de licence de l\'espace client de la boutique Datacurso.';
$string['link_consumptionhistory'] = 'Historique de consommation des crédits';
$string['link_generalreport'] = 'Rapport général';
$string['link_generalreport_datacurso'] = 'Rapport général Datacurso IA';
$string['link_listplugings'] = 'Liste des plugins Datacurso';
$string['link_plugin'] = 'Lien';
$string['link_report_statistic'] = 'Rapport de statistiques générales';
$string['link_webservice_config'] = 'Configuration du service web Datacurso';
$string['live_log'] = 'Journal en direct';
$string['message_no_there_plugins'] = 'Aucun plugin disponible';
$string['missing'] = 'Manquant';
$string['needs_repair'] = 'Nécessite une réparation';
$string['nodata'] = 'Aucune information trouvée';
$string['not_assigned'] = 'Non attribué';
$string['not_configured'] = 'Non configuré';
$string['not_created'] = 'Non créé';
$string['notenoughtokens'] = 'Crédits IA insuffisants. Veuillez visiter <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gérer les crédits</a> dans la boutique Datacurso pour allouer ou acheter plus de crédits. Ou contactez votre administrateur.';
$string['of'] = 'de';
$string['orgid'] = 'ID d\'organisation';
$string['orgid_desc'] = 'Entrez l\'identifiant de votre organisation dans le service Datacurso.';
$string['pageinfo'] = 'Page {$a->current} sur {$a->totalpages} ({$a->total} enregistrements)';
$string['pending'] = 'En attente';
$string['plugin'] = 'Plugin';
$string['plugindesc_assign_ai'] = 'Réviser les devoirs avec l\'assistance de l\'IA.';
$string['plugindesc_coursegen'] = 'Créer des cours complets, des activités et des ressources avec l\'IA.';
$string['plugindesc_datacurso_ratings'] = 'Permet aux étudiants de noter les activités et les ressources ; les enseignants et administrateurs peuvent ensuite générer une analyse de cours basée sur l\'IA.';
$string['plugindesc_dttutor'] = 'Discuter avec un tuteur IA dans le cours.';
$string['plugindesc_forum_ai'] = 'Étendre les forums avec une analyse alimentée par l\'IA pour générer automatiquement des résumés.';
$string['plugindesc_lifestory'] = 'Rapport et analyse alimentés par l\'IA des progrès académiques de l\'étudiant.';
$string['plugindesc_smartrules'] = 'Créer des activités automatisées basées sur les conditions antérieures des étudiants.';
$string['plugindesc_socialcert'] = 'Générer automatiquement des certificats personnalisés à la fin du cours.';
$string['pluginname'] = 'Fournisseur IA Datacurso';
$string['pluginname_assign_ai'] = 'Devoir IA';
$string['pluginname_coursegen'] = 'Créateur de cours IA';
$string['pluginname_datacurso_ratings'] = 'Notation d\'activités IA';
$string['pluginname_dttutor'] = 'Tuteur IA';
$string['pluginname_forum_ai'] = 'Forum IA';
$string['pluginname_lifestory'] = 'Histoire de vie de l\'étudiant IA';
$string['pluginname_smartrules'] = 'SmartRules IA';
$string['pluginname_socialcert'] = 'Partager certificat IA';
$string['privacy:metadata'] = 'Le plugin Fournisseur IA Datacurso ne stocke aucune donnée personnelle localement. Toutes les données sont traitées par les services IA externes de Datacurso.';
$string['privacy:metadata:aiprovider_datacurso'] = 'Charges utiles des demandes IA Datacurso envoyées au service externe.';
$string['privacy:metadata:aiprovider_datacurso:externalpurpose'] = 'Ces données sont envoyées à Datacurso IA pour répondre à l\'action demandée.';
$string['privacy:metadata:aiprovider_datacurso:numberimages'] = 'Nombre total d\'images demandées au service IA.';
$string['privacy:metadata:aiprovider_datacurso:prompt'] = 'Le texte d\'invite fourni au service IA.';
$string['privacy:metadata:aiprovider_datacurso:userid'] = 'L\'ID utilisateur Moodle effectuant la demande IA.';
$string['read_context_course'] = 'Lire le contexte pour la création de cours IA';
$string['read_context_course_model'] = 'Télécharger le modèle académique pour la création de cours IA';
$string['registers'] = 'Enregistrements';
$string['registration_error'] = 'Dernière erreur';
$string['registration_last'] = 'Enregistrement';
$string['registration_lastsent'] = 'Dernier envoi';
$string['registration_notverified'] = 'Enregistrement non vérifié';
$string['registration_status'] = 'Dernier statut';
$string['registration_verified'] = 'Enregistrement vérifié';
$string['registrationapibearer'] = 'Jeton bearer d\'enregistrement';
$string['registrationapibearer_desc'] = 'Jeton bearer utilisé pour authentifier la demande d\'enregistrement.';
$string['registrationapiurl'] = 'URL du point de terminaison d\'enregistrement';
$string['registrationapiurl_desc'] = 'Point de terminaison pour recevoir la charge utile d\'enregistrement du site. Par défaut : http://localhost:8001/register-site';
$string['registrationsettings'] = 'API d\'enregistrement';
$string['remainingtokens'] = 'Solde restant';
$string['responseinvalidai'] = 'Réponse invalide du service IA.';
$string['responseinvalidaimage'] = 'Réponse invalide du service IA (pas d\'image).';
$string['responseinvalidaimagecreate'] = 'Impossible de créer le fichier image.';
$string['rest_enabled'] = 'Protocole REST activé';
$string['service'] = 'Service';
$string['showrows'] = 'Afficher les lignes';
$string['tokens'] = 'Crédits';
$string['tokens_available'] = 'Crédits disponibles';
$string['tokensconsumed'] = 'Crédits consommés';
$string['tokensconsumedday'] = 'Crédits consommés par jour';
$string['tokensconsumedmonth'] = 'Crédits consommés par mois';
$string['tokensnotsufficient'] = 'Crédits IA insuffisants. Solde actuel : {$a->current}. Minimum requis : {$a->required}. Veuillez visiter <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gérer les crédits</a> dans la boutique Datacurso pour allouer ou acheter plus de crédits. Ou contactez votre administrateur.';
$string['tokensused'] = 'Crédits utilisés';
$string['tokenthreshold'] = 'Seuil de crédits';
$string['tokenthreshold_desc'] = 'Nombre de crédits à partir duquel une notification sera affichée pour en acheter davantage.';
$string['total_consumed'] = 'Crédits consommés';
$string['userid'] = 'Utilisateur';
$string['userratelimit'] = 'Limite de requêtes par utilisateur';
$string['userratelimit_desc'] = 'Nombre maximum de requêtes autorisées par heure pour chaque utilisateur individuel.';
$string['verified'] = 'Vérifié';
$string['webserviceconfig_current'] = 'Configuration actuelle';
$string['webserviceconfig_desc'] = 'Configure automatiquement un service web dédié pour le service IA Datacurso, lui permettant d\'extraire en toute sécurité les informations de la plateforme telles que les données de base des utilisateurs, les cours et les activités pour une meilleure contextualisation de l\'IA. Cette configuration crée un utilisateur de service, attribue le rôle nécessaire, configure le service externe, génère un jeton sécurisé et active le protocole REST en un clic. Remarque : la valeur du jeton n\'est pas affichée pour des raisons de sécurité.';
$string['webserviceconfig_heading'] = 'Configuration automatique du service web';
$string['webserviceconfig_site'] = 'Informations sur le site';
$string['webserviceconfig_status'] = 'Statut';
$string['webserviceconfig_title'] = 'Configuration des services web Datacurso';
$string['workplace'] = 'Est-ce Moodle Workplace ?';
$string['workplace_desc'] = 'Définit si l\'en-tête X-Workplace doit être envoyé avec la valeur true (Workplace) ou false (Moodle standard).';
$string['ws_activity'] = 'Journal d\'activité';
$string['ws_btn_regenerate'] = 'Régénérer le jeton';
$string['ws_btn_retry'] = 'Réessayer la configuration';
$string['ws_btn_setup'] = 'Configurer le service web';
$string['ws_enabled'] = 'Services web activés';
$string['ws_error_missing_setup'] = 'Service ou utilisateur introuvable. Exécutez d\'abord la configuration.';
$string['ws_error_missing_token'] = 'Jeton introuvable. Générez-le d\'abord.';
$string['ws_error_regenerate_token'] = 'Erreur lors de la régénération du jeton.';
$string['ws_error_registration'] = 'Erreur lors de l\'enregistrement du jeton du service web.';
$string['ws_error_setup'] = 'Erreur lors de la configuration du service web.';
$string['ws_role'] = 'Rôle du service';
$string['ws_service'] = 'Service externe';
$string['ws_step_enableauth'] = 'Activation du plugin d\'authentification des services web…';
$string['ws_step_enablerest'] = 'Activation du protocole REST…';
$string['ws_step_enablews'] = 'Activation des services web du site…';
$string['ws_step_registration_sent'] = 'Demande d\'enregistrement envoyée.';
$string['ws_step_role_assign'] = 'Attribution du rôle à l\'utilisateur du service…';
$string['ws_step_role_caps'] = 'Définition des capacités de rôle requises…';
$string['ws_step_role_create'] = 'Création du rôle "{$a}"…';
$string['ws_step_role_exists'] = 'Le rôle existe déjà, utilisation de l\'ID {$a}…';
$string['ws_step_service_enable'] = 'Création/Activation du service externe…';
$string['ws_step_service_functions'] = 'Ajout des fonctions principales communes au service…';
$string['ws_step_service_user'] = 'Autorisation de l\'utilisateur pour le service…';
$string['ws_step_setup'] = 'Démarrage de la configuration…';
$string['ws_step_token_create'] = 'Vérification de l\'existence du jeton…';
$string['ws_step_token_generated'] = 'Jeton généré.';
$string['ws_step_token_regenerated'] = 'Jeton régénéré.';
$string['ws_step_token_regenerating'] = 'Régénération du jeton…';
$string['ws_step_token_retry'] = 'Nouvelle tentative de configuration…';
$string['ws_step_user_check'] = 'Vérification de l\'existence de l\'utilisateur "{$a}"…';
$string['ws_step_user_create'] = 'Création de l\'utilisateur du service "{$a}"…';
$string['ws_tokenexists'] = 'Le jeton existe';
$string['ws_user'] = 'Utilisateur du service';
$string['ws_userassigned'] = 'Rôle attribué à l\'utilisateur';
