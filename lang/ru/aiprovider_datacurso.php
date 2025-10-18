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

$string['action'] = 'Действие';
$string['action:generate_image:endpoint'] = 'Конечная точка API';
$string['action:generate_image:endpoint_desc'] = 'Конечная точка для генерации изображений';
$string['action:generate_text:endpoint'] = 'Конечная точка API';
$string['action:generate_text:endpoint_desc'] = 'Конечная точка для генерации текста';
$string['action:generate_text:instruction'] = 'Системная инструкция';
$string['action:generate_text:instruction_desc'] = 'Эта инструкция отправляется модели ИИ вместе с запросом пользователя. Редактирование этой инструкции не рекомендуется, если это не является абсолютно необходимым.';
$string['action:summarise_text:endpoint'] = 'Конечная точка API';
$string['action:summarise_text:endpoint_desc'] = 'Конечная точка для генерации текста';
$string['action:summarise_text:instruction'] = 'Системная инструкция';
$string['action:summarise_text:instruction_desc'] = 'Эта инструкция отправляется модели ИИ вместе с запросом пользователя. Редактирование этой инструкции не рекомендуется, если это не является абсолютно необходимым.';
$string['all'] = 'Все';
$string['apikey'] = 'Ключ API';
$string['apikey_desc'] = 'Введите ключ API от вашего сервиса Datacurso для подключения ИИ.';
$string['apiurl'] = 'Базовый URL API';
$string['apiurl_desc'] = 'Введите базовый URL сервиса для подключения к API Datacurso.';
$string['assigned'] = 'Назначено';
$string['chart_actions'] = 'Распределение токенов по сервисам';
$string['chart_tokens_by_day'] = 'Потребление токенов по дням';
$string['chart_tokens_by_month'] = 'Количество токенов, потребленных за месяц';
$string['configured'] = 'Настроено';
$string['contextwstoken'] = 'Токен веб-сервиса для контекста курса';
$string['contextwstoken_desc'] = 'Токен, используемый ИИ для получения информации о курсе (контекст). Безопасно хранится. Создать/управлять токенами в Администрирование сайта > Сервер > Веб-сервисы > Управление токенами.';
$string['created'] = 'Создано';
$string['datacurso:manage'] = 'Управлять настройками провайдера ИИ';
$string['datacurso:use'] = 'Использовать сервисы ИИ Datacurso';
$string['datacurso:viewreports'] = 'Просматривать отчеты об использовании ИИ';
$string['description'] = 'Описание';
$string['descriptionpagelistplugins'] = 'Здесь вы можете найти список плагинов, совместимых с провайдером Datacurso';
$string['disabled'] = 'Отключено';
$string['enabled'] = 'Включено';
$string['enableglobalratelimit'] = 'Включить глобальное ограничение';
$string['enableglobalratelimit_desc'] = 'Если включено, для всех пользователей будет применено глобальное ограничение запросов в час.';
$string['enableuserratelimit'] = 'Включить ограничение на пользователя';
$string['enableuserratelimit_desc'] = 'Если включено, у каждого пользователя будет ограничение запросов в час.';
$string['exists'] = 'Существует';
$string['generate_activitie'] = 'Создать активность или ресурс с помощью ИИ';
$string['generate_analysis_comments'] = 'Создать анализ оценок активности/ресурса с помощью ИИ';
$string['generate_analysis_course'] = 'Создать анализ оценок курса с помощью ИИ';
$string['generate_analysis_general'] = 'Создать общий анализ оценок с помощью ИИ';
$string['generate_analysis_story_student'] = 'Создать анализ истории студента с помощью ИИ';
$string['generate_assign_answer'] = 'Создать проверку задания с помощью ИИ';
$string['generate_certificate_answer'] = 'Создать сообщение сертификата с помощью ИИ';
$string['generate_creation_course'] = 'Создать полный курс с помощью ИИ';
$string['generate_forum_chat'] = 'Создать ответ на форуме с помощью ИИ';
$string['generate_image'] = 'Создать изображение с помощью ИИ';
$string['generate_plan_course'] = 'Создать план создания курса с помощью ИИ';
$string['generate_summary'] = 'Создать резюме с помощью ИИ';
$string['generate_text'] = 'Создать текст с помощью ИИ';
$string['globalratelimit'] = 'Глобальное ограничение запросов';
$string['globalratelimit_desc'] = 'Максимальное количество запросов в час для всей системы.';
$string['goto'] = 'Перейти к отчету';
$string['gotopage'] = 'Перейти на страницу';
$string['id'] = 'ID';
$string['installed'] = 'Установлено';
$string['invalidlicensekey'] = 'Недействительный лицензионный ключ';
$string['last_sent'] = 'Последняя отправка';
$string['licensekey'] = 'Лицензионный ключ';
$string['licensekey_desc'] = 'Введите лицензионный ключ, полученный из личного кабинета в магазине Datacurso.';
$string['link_consumptionhistory'] = 'История потребления токенов';
$string['link_generalreport'] = 'Общий отчет';
$string['link_generalreport_datacurso'] = 'Общий отчет Datacurso AI';
$string['link_listplugings'] = 'Список плагинов Datacurso';
$string['link_plugin'] = 'Ссылка';
$string['link_report_statistic'] = 'Отчет общей статистики';
$string['link_webservice_config'] = 'Настройка веб-сервиса Datacurso';
$string['live_log'] = 'Журнал в реальном времени';
$string['message_no_there_plugins'] = 'Нет доступных плагинов';
$string['missing'] = 'Отсутствует';
$string['needs_repair'] = 'Требуется ремонт';
$string['nodata'] = 'Информация не найдена';
$string['not_assigned'] = 'Не назначено';
$string['not_configured'] = 'Не настроено';
$string['not_created'] = 'Не создано';
$string['orgid'] = 'ID организации';
$string['orgid_desc'] = 'Введите идентификатор вашей организации в сервисе Datacurso.';
$string['pending'] = 'Ожидание';
$string['plugin'] = 'Плагин';
$string['pluginname'] = 'Провайдер ИИ Datacurso';
$string['privacy:metadata'] = 'Плагин Провайдер ИИ Datacurso не хранит никаких персональных данных локально. Все данные обрабатываются внешними сервисами ИИ Datacurso.';
$string['privacy:metadata:aiprovider_datacurso'] = 'Данные запроса, отправляемые во внешний сервис Datacurso AI.';
$string['privacy:metadata:aiprovider_datacurso:externalpurpose'] = 'Эти данные передаются в Datacurso AI для выполнения запрошенного действия.';
$string['privacy:metadata:aiprovider_datacurso:numberimages'] = 'Общее количество изображений, запрошенных у сервиса ИИ.';
$string['privacy:metadata:aiprovider_datacurso:prompt'] = 'Текст подсказки, передаваемый сервису ИИ.';
$string['privacy:metadata:aiprovider_datacurso:userid'] = 'Идентификатор пользователя Moodle, отправившего запрос ИИ.';
$string['read_context_course'] = 'Прочитать контекст для создания курса с помощью ИИ';
$string['read_context_course_model'] = 'Загрузить академическую модель для создания курса с помощью ИИ';
$string['registration_error'] = 'Последняя ошибка';
$string['registration_last'] = 'Регистрация';
$string['registration_lastsent'] = 'Последняя отправка';
$string['registration_notverified'] = 'Регистрация не подтверждена';
$string['registration_status'] = 'Последний статус';
$string['registration_verified'] = 'Регистрация подтверждена';
$string['registrationapibearer'] = 'Bearer-токен регистрации';
$string['registrationapibearer_desc'] = 'Bearer-токен, используемый для аутентификации запроса регистрации.';
$string['registrationapiurl'] = 'URL конечной точки регистрации';
$string['registrationapiurl_desc'] = 'Конечная точка для получения данных регистрации сайта. По умолчанию: http://localhost:8001/register-site';
$string['registrationsettings'] = 'API регистрации';
$string['remainingtokens'] = 'Остаток баланса';
$string['rest_enabled'] = 'Протокол REST включен';
$string['service'] = 'Сервис';
$string['showrows'] = 'Показать строки';
$string['tokens_available'] = 'Доступные токены';
$string['tokensused'] = 'Использованные токены';
$string['tokenthreshold'] = 'Порог токенов';
$string['tokenthreshold_desc'] = 'Количество токенов, при котором будет показано уведомление о покупке дополнительных.';
$string['total_consumed'] = 'Всего потреблено';
$string['userid'] = 'Пользователь';
$string['userratelimit'] = 'Ограничение запросов на пользователя';
$string['userratelimit_desc'] = 'Максимальное количество запросов в час для каждого отдельного пользователя.';
$string['verified'] = 'Подтверждено';
$string['webserviceconfig_current'] = 'Текущая конфигурация';
$string['webserviceconfig_desc'] = 'Автоматически настраивает выделенный веб-сервис для сервиса ИИ Datacurso, позволяя ему безопасно извлекать информацию платформы, такую как основные данные пользователей, курсы и активности для лучшей контекстуализации ИИ. Эта настройка создает служебного пользователя, назначает необходимую роль, настраивает внешний сервис, генерирует безопасный токен и включает протокол REST одним щелчком мыши. Примечание: значение токена не отображается по соображениям безопасности.';
$string['webserviceconfig_heading'] = 'Автоматическая настройка веб-сервиса';
$string['webserviceconfig_site'] = 'Информация о сайте';
$string['webserviceconfig_status'] = 'Статус';
$string['webserviceconfig_title'] = 'Настройка веб-сервиса Datacurso';
$string['workplace'] = 'Это Moodle Workplace?';
$string['workplace_desc'] = 'Определяет, должен ли заголовок X-Workplace отправляться со значением true (Workplace) или false (стандартный Moodle).';
$string['ws_activity'] = 'Журнал активности';
$string['ws_btn_regenerate'] = 'Перегенерировать токен';
$string['ws_btn_retry'] = 'Повторить настройку';
$string['ws_btn_setup'] = 'Настроить веб-сервис';
$string['ws_enabled'] = 'Веб-сервисы включены';
$string['ws_error_missing_setup'] = 'Сервис или пользователь не найдены. Сначала выполните настройку.';
$string['ws_error_missing_token'] = 'Токен не найден. Сначала сгенерируйте его.';
$string['ws_error_regenerate_token'] = 'Ошибка при перегенерации токена.';
$string['ws_error_registration'] = 'Ошибка при регистрации токена веб-сервиса.';
$string['ws_error_setup'] = 'Ошибка при настройке веб-сервиса.';
$string['ws_role'] = 'Роль сервиса';
$string['ws_service'] = 'Внешний сервис';
$string['ws_step_enableauth'] = 'Включение плагина аутентификации веб-сервисов…';
$string['ws_step_enablerest'] = 'Включение протокола REST…';
$string['ws_step_enablews'] = 'Включение веб-сервисов сайта…';
$string['ws_step_registration_sent'] = 'Запрос регистрации отправлен.';
$string['ws_step_role_assign'] = 'Назначение роли служебному пользователю…';
$string['ws_step_role_caps'] = 'Установка необходимых возможностей роли…';
$string['ws_step_role_create'] = 'Создание роли "{$a}"…';
$string['ws_step_role_exists'] = 'Роль уже существует, используется ID {$a}…';
$string['ws_step_service_enable'] = 'Создание/Включение внешнего сервиса…';
$string['ws_step_service_functions'] = 'Добавление общих основных функций в сервис…';
$string['ws_step_service_user'] = 'Авторизация пользователя для сервиса…';
$string['ws_step_setup'] = 'Начало настройки…';
$string['ws_step_token_create'] = 'Проверка существования токена…';
$string['ws_step_token_generated'] = 'Токен сгенерирован.';
$string['ws_step_token_regenerated'] = 'Токен перегенерирован.';
$string['ws_step_token_regenerating'] = 'Перегенерация токена…';
$string['ws_step_token_retry'] = 'Повтор настройки…';
$string['ws_step_user_check'] = 'Проверка существования пользователя "{$a}"…';
$string['ws_step_user_create'] = 'Создание служебного пользователя "{$a}"…';
$string['ws_tokenexists'] = 'Токен существует';
$string['ws_user'] = 'Служебный пользователь';
$string['ws_userassigned'] = 'Роль назначена пользователю';
