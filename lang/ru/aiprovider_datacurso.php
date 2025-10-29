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
$string['action:generate_text:instruction_desc'] = 'Эта инструкция отправляется модели ИИ вместе с запросом пользователя. Не рекомендуется редактировать эту инструкцию, если это не является абсолютно необходимым.';
$string['action:summarise_text:endpoint'] = 'Конечная точка API';
$string['action:summarise_text:endpoint_desc'] = 'Конечная точка для генерации текста';
$string['action:summarise_text:instruction'] = 'Системная инструкция';
$string['action:summarise_text:instruction_desc'] = 'Эта инструкция отправляется модели ИИ вместе с запросом пользователя. Не рекомендуется редактировать эту инструкцию, если это не является абсолютно необходимым.';
$string['all'] = 'Все';
$string['apikey'] = 'Ключ API';
$string['apikey_desc'] = 'Введите ключ API из вашего сервиса Datacurso для подключения ИИ.';
$string['apiurl'] = 'Базовый URL API';
$string['apiurl_desc'] = 'Введите базовый URL сервиса для подключения к API Datacurso.';
$string['assigned'] = 'Назначено';
$string['chart_actions'] = 'Распределение кредитов по сервисам';
$string['chart_tokens_by_day'] = 'Потребление кредитов по дням';
$string['chart_tokens_by_month'] = 'Количество потребленных кредитов в месяц';
$string['configured'] = 'Настроено';
$string['contextwstoken'] = 'Токен веб-сервиса для контекста курса';
$string['contextwstoken_desc'] = 'Токен, используемый ИИ для получения информации о курсе (контекст). Хранится безопасно. Создавайте/управляйте токенами в Администрирование сайта > Сервер > Веб-сервисы > Управление токенами.';
$string['created'] = 'Создано';
$string['curlerror'] = 'Ошибка cURL API Datacurso: {$a}';
$string['datacurso:manage'] = 'Управление настройками провайдера ИИ';
$string['datacurso:use'] = 'Использование сервисов ИИ Datacurso';
$string['datacurso:viewreports'] = 'Просмотр отчетов об использовании ИИ';
$string['description'] = 'Описание';
$string['descriptionpagelistplugins'] = 'Здесь вы можете найти список плагинов, совместимых с провайдером Datacurso';
$string['disabled'] = 'Отключено';
$string['emptyprompt'] = 'Пустой запрос';
$string['emptyresponse'] = 'Нет ответа от API Datacurso.';
$string['enabled'] = 'Включено';
$string['enableglobalratelimit'] = 'Включить глобальное ограничение';
$string['enableglobalratelimit_desc'] = 'Если включено, будет применяться глобальное ограничение запросов в час для всех пользователей.';
$string['enableuserratelimit'] = 'Включить ограничение для пользователя';
$string['enableuserratelimit_desc'] = 'Если включено, каждый пользователь будет иметь почасовое ограничение запросов.';
$string['errorgetbalancecredits'] = 'Не удалось получить баланс кредитов из внешнего API';
$string['errorinitinformation'] = 'Не удалось получить начальную информацию.';
$string['exists'] = 'Существует';
$string['forbidden'] = 'Вам не разрешено выполнять это действие с текущей лицензией. Пожалуйста, проверьте вашу лицензию и доступные кредиты в <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Управление кредитами</a> в магазине Datacurso.';
$string['generate_activitie'] = 'Создать активность или ресурс с помощью ИИ';
$string['generate_ai_reinforcement_activity'] = 'Создать активность для закрепления с ИИ';
$string['generate_analysis_comments'] = 'Создать анализ оценки активности/ресурса с помощью ИИ';
$string['generate_analysis_course'] = 'Создать анализ оценки курса с помощью ИИ';
$string['generate_analysis_general'] = 'Создать общий анализ оценки с помощью ИИ';
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
$string['globalratelimit_desc'] = 'Максимальное количество запросов, разрешенных в час для всей системы.';
$string['goto'] = 'Перейти к отчету';
$string['gotopage'] = 'Перейти на страницу';
$string['httperror'] = 'Неожиданная ошибка при обработке вашего запроса (HTTP {$a}). Пожалуйста, попробуйте позже. Если проблема сохраняется, свяжитесь с администратором сайта.';
$string['id'] = 'ID';
$string['installed'] = 'Установлено';
$string['invalidlicensekey'] = 'Ключ лицензии истек или недействителен. Пожалуйста, перейдите в <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Управление кредитами</a> в магазине Datacurso для обновления или покупки новой лицензии.';
$string['json_encode_failed'] = 'Ошибка кодирования JSON';
$string['jsondecodeerror'] = 'Ошибка обработки ответа от API Datacurso: {$a}';
$string['last_sent'] = 'Последняя отправка';
$string['license_not_allowed'] = 'Ваша лицензия не позволяет выполнить этот запрос. Пожалуйста, управляйте своими лицензиями и кредитами в <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Управление кредитами</a> в магазине Datacurso.';
$string['licensekey'] = 'Ключ лицензии';
$string['licensekey_desc'] = 'Введите ключ лицензии из клиентской области магазина Datacurso.';
$string['link_consumptionhistory'] = 'История потребления кредитов';
$string['link_generalreport'] = 'Общий отчет';
$string['link_generalreport_datacurso'] = 'Общий отчет Datacurso ИИ';
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
$string['notenoughtokens'] = 'Недостаточно кредитов ИИ. Пожалуйста, посетите <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Управление кредитами</a> в магазине Datacurso, чтобы выделить или купить больше кредитов. Или свяжитесь с вашим администратором.';
$string['of'] = 'из';
$string['orgid'] = 'ID организации';
$string['orgid_desc'] = 'Введите идентификатор вашей организации в сервисе Datacurso.';
$string['pending'] = 'В ожидании';
$string['plugin'] = 'Плагин';
$string['plugindesc_assign_ai'] = 'Проверка заданий с помощью ИИ.';
$string['plugindesc_coursegen'] = 'Создание полных курсов, активностей и ресурсов с помощью ИИ.';
$string['plugindesc_datacurso_ratings'] = 'Позволяет студентам оценивать активности и ресурсы; преподаватели и администраторы могут затем генерировать анализ курсов на основе ИИ.';
$string['plugindesc_dttutor'] = 'Общение с ИИ-тьютором внутри курса.';
$string['plugindesc_forum_ai'] = 'Расширение форумов с помощью анализа на основе ИИ для автоматического создания резюме.';
$string['plugindesc_lifestory'] = 'Отчет и анализ академического прогресса студента на основе ИИ.';
$string['plugindesc_smartrules'] = 'Создание автоматизированных активностей на основе предыдущих условий студентов.';
$string['plugindesc_socialcert'] = 'Автоматическое создание персонализированных сертификатов при завершении курса.';
$string['pluginname'] = 'Провайдер ИИ Datacurso';
$string['pluginname_assign_ai'] = 'Задание ИИ';
$string['pluginname_coursegen'] = 'Создатель курсов ИИ';
$string['pluginname_datacurso_ratings'] = 'Рейтинг активностей ИИ';
$string['pluginname_dttutor'] = 'Тьютор ИИ';
$string['pluginname_forum_ai'] = 'Форум ИИ';
$string['pluginname_lifestory'] = 'История жизни студента ИИ';
$string['pluginname_smartrules'] = 'SmartRules ИИ';
$string['pluginname_socialcert'] = 'Поделиться сертификатом ИИ';
$string['privacy:metadata'] = 'Плагин Провайдер ИИ Datacurso не хранит никаких персональных данных локально. Все данные обрабатываются внешними сервисами ИИ Datacurso.';
$string['privacy:metadata:aiprovider_datacurso'] = 'Полезные нагрузки запросов ИИ Datacurso, отправляемые во внешний сервис.';
$string['privacy:metadata:aiprovider_datacurso:externalpurpose'] = 'Эти данные отправляются в Datacurso ИИ для выполнения запрошенного действия.';
$string['privacy:metadata:aiprovider_datacurso:numberimages'] = 'Общее количество изображений, запрошенных у сервиса ИИ.';
$string['privacy:metadata:aiprovider_datacurso:prompt'] = 'Текст запроса, предоставленный сервису ИИ.';
$string['privacy:metadata:aiprovider_datacurso:userid'] = 'ID пользователя Moodle, делающего запрос к ИИ.';
$string['read_context_course'] = 'Чтение контекста для создания курса с ИИ';
$string['read_context_course_model'] = 'Загрузка академической модели для создания курса с ИИ';
$string['registers'] = 'Записи';
$string['registration_error'] = 'Последняя ошибка';
$string['registration_last'] = 'Регистрация';
$string['registration_lastsent'] = 'Последняя отправка';
$string['registration_notverified'] = 'Регистрация не подтверждена';
$string['registration_status'] = 'Последний статус';
$string['registration_verified'] = 'Регистрация подтверждена';
$string['registrationapibearer'] = 'Bearer токен регистрации';
$string['registrationapibearer_desc'] = 'Bearer токен, используемый для аутентификации запроса регистрации.';
$string['registrationapiurl'] = 'URL конечной точки регистрации';
$string['registrationapiurl_desc'] = 'Конечная точка для получения полезной нагрузки регистрации сайта. По умолчанию: http://localhost:8001/register-site';
$string['registrationsettings'] = 'API регистрации';
$string['remainingtokens'] = 'Остаток баланса';
$string['responseinvalidai'] = 'Недопустимый ответ от сервиса ИИ.';
$string['responseinvalidaimage'] = 'Недопустимый ответ от сервиса ИИ (нет изображения).';
$string['responseinvalidaimagecreate'] = 'Не удалось создать файл изображения.';
$string['rest_enabled'] = 'Протокол REST включен';
$string['service'] = 'Сервис';
$string['showrows'] = 'Показать строки';
$string['tokens'] = 'Кредиты';
$string['tokens_available'] = 'Доступные кредиты';
$string['tokensconsumed'] = 'Потребленные кредиты';
$string['tokensconsumedday'] = 'Кредиты, потребленные за день';
$string['tokensconsumedmonth'] = 'Кредиты, потребленные за месяц';
$string['tokensnotsufficient'] = 'Недостаточно кредитов ИИ. Текущий баланс: {$a->current}. Минимально необходимо: {$a->required}. Пожалуйста, посетите <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Управление кредитами</a> в магазине Datacurso, чтобы выделить или купить больше кредитов. Или свяжитесь с вашим администратором.';
$string['tokensused'] = 'Использованные кредиты';
$string['tokenthreshold'] = 'Порог кредитов';
$string['tokenthreshold_desc'] = 'Количество кредитов, при котором будет показано уведомление о необходимости покупки дополнительных кредитов.';
$string['total_consumed'] = 'Потребленные кредиты';
$string['userid'] = 'Пользователь';
$string['userratelimit'] = 'Ограничение запросов на пользователя';
$string['userratelimit_desc'] = 'Максимальное количество запросов, разрешенных в час для каждого отдельного пользователя.';
$string['verified'] = 'Проверено';
$string['webserviceconfig_current'] = 'Текущая конфигурация';
$string['webserviceconfig_desc'] = 'Автоматически настраивает выделенный веб-сервис для сервиса ИИ Datacurso, позволяя ему безопасно извлекать информацию платформы, такую как основные данные пользователей, курсы и активности для лучшей контекстуализации ИИ. Эта настройка создает сервисного пользователя, назначает необходимую роль, настраивает внешний сервис, генерирует безопасный токен и включает протокол REST одним кликом. Примечание: Значение токена не отображается по соображениям безопасности.';
$string['webserviceconfig_heading'] = 'Автоматическая настройка веб-сервиса';
$string['webserviceconfig_site'] = 'Информация о сайте';
$string['webserviceconfig_status'] = 'Статус';
$string['webserviceconfig_title'] = 'Конфигурация веб-сервисов Datacurso';
$string['workplace'] = 'Это Moodle Workplace?';
$string['workplace_desc'] = 'Определяет, следует ли отправлять заголовок X-Workplace со значением true (Workplace) или false (стандартный Moodle).';
$string['ws_activity'] = 'Журнал активности';
$string['ws_btn_regenerate'] = 'Перегенерировать токен';
$string['ws_btn_retry'] = 'Повторить настройку';
$string['ws_btn_setup'] = 'Настроить веб-сервис';
$string['ws_enabled'] = 'Веб-сервисы включены';
$string['ws_error_missing_setup'] = 'Сервис или пользователь не найден. Сначала выполните настройку.';
$string['ws_error_missing_token'] = 'Токен не найден. Сначала сгенерируйте его.';
$string['ws_error_regenerate_token'] = 'Ошибка при перегенерации токена.';
$string['ws_error_registration'] = 'Ошибка при регистрации токена веб-сервиса.';
$string['ws_error_setup'] = 'Ошибка при настройке веб-сервиса.';
$string['ws_role'] = 'Роль сервиса';
$string['ws_service'] = 'Внешний сервис';
$string['ws_step_enableauth'] = 'Включение плагина аутентификации веб-сервисов…';
$string['ws_step_enablerest'] = 'Включение протокола REST…';
$string['ws_step_enablews'] = 'Включение веб-сервисов сайта…';
$string['ws_step_registration_sent'] = 'Запрос на регистрацию отправлен.';
$string['ws_step_role_assign'] = 'Назначение роли сервисному пользователю…';
$string['ws_step_role_caps'] = 'Установка необходимых прав роли…';
$string['ws_step_role_create'] = 'Создание роли "{$a}"…';
$string['ws_step_role_exists'] = 'Роль уже существует, используется ID {$a}…';
$string['ws_step_service_enable'] = 'Создание/Включение внешнего сервиса…';
$string['ws_step_service_functions'] = 'Добавление общих основных функций в сервис…';
$string['ws_step_service_user'] = 'Авторизация пользователя для сервиса…';
$string['ws_step_setup'] = 'Запуск настройки…';
$string['ws_step_token_create'] = 'Проверка существования токена…';
$string['ws_step_token_generated'] = 'Токен сгенерирован.';
$string['ws_step_token_regenerated'] = 'Токен перегенерирован.';
$string['ws_step_token_regenerating'] = 'Перегенерация токена…';
$string['ws_step_token_retry'] = 'Повтор настройки…';
$string['ws_step_user_check'] = 'Проверка существования пользователя "{$a}"…';
$string['ws_step_user_create'] = 'Создание сервисного пользователя "{$a}"…';
$string['ws_tokenexists'] = 'Токен существует';
$string['ws_user'] = 'Сервисный пользователь';
$string['ws_userassigned'] = 'Роль назначена пользователю';
