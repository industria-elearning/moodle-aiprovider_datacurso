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

$string['action'] = 'Ação';
$string['action:generate_image:endpoint'] = 'Endpoint da API';
$string['action:generate_image:endpoint_desc'] = 'O endpoint para gerar imagens';
$string['action:generate_text:endpoint'] = 'Endpoint da API';
$string['action:generate_text:endpoint_desc'] = 'O endpoint para gerar texto';
$string['action:generate_text:instruction'] = 'Instrução do sistema';
$string['action:generate_text:instruction_desc'] = 'Esta instrução é enviada ao modelo de IA junto com a solicitação do usuário. Não é recomendado editar esta instrução, a menos que seja absolutamente necessário.';
$string['action:summarise_text:endpoint'] = 'Endpoint da API';
$string['action:summarise_text:endpoint_desc'] = 'O endpoint para gerar texto';
$string['action:summarise_text:instruction'] = 'Instrução do sistema';
$string['action:summarise_text:instruction_desc'] = 'Esta instrução é enviada ao modelo de IA junto com a solicitação do usuário. Não é recomendado editar esta instrução, a menos que seja absolutamente necessário.';
$string['all'] = 'Todos';
$string['apikey'] = 'Chave da API';
$string['apikey_desc'] = 'Insira a chave da API do seu serviço Datacurso para conectar a IA.';
$string['apiurl'] = 'URL base da API';
$string['apiurl_desc'] = 'Insira a URL base do serviço para conectar à API Datacurso.';
$string['assigned'] = 'Atribuído';
$string['chart_actions'] = 'Distribuição de tokens por serviço';
$string['chart_tokens_by_day'] = 'Consumo de tokens por dia';
$string['chart_tokens_by_month'] = 'Número de tokens consumidos por mês';
$string['configured'] = 'Configurado';
$string['contextwstoken'] = 'Token de serviço web para contexto do curso';
$string['contextwstoken_desc'] = 'Token usado pela IA para recuperar informações do curso (contexto). Armazenado com segurança. Criar/gerenciar tokens em Administração do site > Servidor > Serviços web > Gerenciar tokens.';
$string['created'] = 'Criado';
$string['datacurso:manage'] = 'Gerenciar configurações do provedor de IA';
$string['datacurso:use'] = 'Usar serviços de IA Datacurso';
$string['datacurso:viewreports'] = 'Ver relatórios de uso de IA';
$string['description'] = 'Descrição';
$string['descriptionpagelistplugins'] = 'Aqui você pode encontrar a lista de plugins compatíveis com o provedor Datacurso';
$string['disabled'] = 'Desabilitado';
$string['enabled'] = 'Habilitado';
$string['enableglobalratelimit'] = 'Habilitar limite global';
$string['enableglobalratelimit_desc'] = 'Se habilitado, um limite global de solicitações por hora será aplicado para todos os usuários.';
$string['enableuserratelimit'] = 'Habilitar limite por usuário';
$string['enableuserratelimit_desc'] = 'Se habilitado, cada usuário terá um limite de solicitações por hora.';
$string['exists'] = 'Existe';
$string['generate_activitie'] = 'Gerar atividade ou recurso com IA';
$string['generate_analysis_comments'] = 'Gerar análise de avaliações de uma atividade/recurso com IA';
$string['generate_analysis_course'] = 'Gerar análise de avaliações do curso com IA';
$string['generate_analysis_general'] = 'Gerar análise geral de avaliações com IA';
$string['generate_analysis_story_student'] = 'Gerar análise do histórico do estudante com IA';
$string['generate_assign_answer'] = 'Gerar revisão de tarefa com IA';
$string['generate_certificate_answer'] = 'Gerar mensagem de certificado com IA';
$string['generate_creation_course'] = 'Criar curso completo com IA';
$string['generate_forum_chat'] = 'Gerar resposta de fórum com IA';
$string['generate_image'] = 'Gerar imagem com IA';
$string['generate_plan_course'] = 'Gerar plano de criação de curso com IA';
$string['generate_summary'] = 'Gerar resumo com IA';
$string['generate_text'] = 'Gerar texto com IA';
$string['globalratelimit'] = 'Limite global de solicitações';
$string['globalratelimit_desc'] = 'Número máximo de solicitações permitidas por hora para todo o sistema.';
$string['goto'] = 'Ir para o relatório';
$string['gotopage'] = 'Ir para a página';
$string['id'] = 'ID';
$string['installed'] = 'Instalado';
$string['invalidlicensekey'] = 'Chave de licença inválida';
$string['last_sent'] = 'Último enviado';
$string['licensekey'] = 'Chave de licença';
$string['licensekey_desc'] = 'Insira a chave de licença obtida na área do cliente da loja Datacurso.';
$string['link_consumptionhistory'] = 'Histórico de consumo de tokens';
$string['link_generalreport'] = 'Relatório geral';
$string['link_generalreport_datacurso'] = 'Relatório geral Datacurso AI';
$string['link_listplugings'] = 'Lista de plugins Datacurso';
$string['link_plugin'] = 'Link';
$string['link_report_statistic'] = 'Relatório de estatísticas gerais';
$string['link_webservice_config'] = 'Configuração de serviço web Datacurso';
$string['live_log'] = 'Log ao vivo';
$string['message_no_there_plugins'] = 'Nenhum plugin disponível';
$string['missing'] = 'Faltando';
$string['needs_repair'] = 'Precisa de reparo';
$string['nodata'] = 'Nenhuma informação encontrada';
$string['not_assigned'] = 'Não atribuído';
$string['not_configured'] = 'Não configurado';
$string['not_created'] = 'Não criado';
$string['orgid'] = 'ID da organização';
$string['orgid_desc'] = 'Insira o identificador da sua organização no serviço Datacurso.';
$string['pending'] = 'Pendente';
$string['plugin'] = 'Plugin';
$string['pluginname'] = 'Provedor de IA Datacurso';
$string['privacy:metadata'] = 'O plugin Provedor de IA Datacurso não armazena nenhum dado pessoal localmente. Todos os dados são processados pelos serviços externos de IA da Datacurso.';
$string['privacy:metadata:datacurso_ai_services'] = 'Dados enviados aos serviços de IA Datacurso para processamento.';
$string['privacy:metadata:datacurso_ai_services:request_data'] = 'Os dados enviados ao serviço de IA para processamento.';
$string['privacy:metadata:datacurso_ai_services:response_data'] = 'A resposta recebida do serviço de IA.';
$string['privacy:metadata:datacurso_ai_services:timestamp'] = 'Quando a solicitação de IA foi feita.';
$string['privacy:metadata:datacurso_ai_services:tokens_consumed'] = 'Número de tokens consumidos na solicitação.';
$string['privacy:metadata:datacurso_ai_services:userid'] = 'O ID do usuário que faz a solicitação de IA.';
$string['read_context_course'] = 'Ler contexto para criação de curso com IA';
$string['read_context_course_model'] = 'Carregar modelo acadêmico para criação de curso com IA';
$string['registration_error'] = 'Último erro';
$string['registration_last'] = 'Registro';
$string['registration_lastsent'] = 'Último enviado';
$string['registration_notverified'] = 'Registro não verificado';
$string['registration_status'] = 'Último status';
$string['registration_verified'] = 'Registro verificado';
$string['registrationapibearer'] = 'Token bearer de registro';
$string['registrationapibearer_desc'] = 'Token bearer usado para autenticar a solicitação de registro.';
$string['registrationapiurl'] = 'URL do endpoint de registro';
$string['registrationapiurl_desc'] = 'Endpoint para receber a carga de registro do site. Padrão: http://localhost:8001/register-site';
$string['registrationsettings'] = 'API de registro';
$string['remainingtokens'] = 'Saldo restante';
$string['rest_enabled'] = 'Protocolo REST habilitado';
$string['service'] = 'Serviço';
$string['showrows'] = 'Mostrar linhas';
$string['tokens_available'] = 'Tokens disponíveis';
$string['tokensused'] = 'Tokens usados';
$string['tokenthreshold'] = 'Limite de tokens';
$string['tokenthreshold_desc'] = 'Número de tokens a partir do qual uma notificação será exibida para comprar mais.';
$string['total_consumed'] = 'Total consumido';
$string['userid'] = 'Usuário';
$string['userratelimit'] = 'Limite de solicitações por usuário';
$string['userratelimit_desc'] = 'Número máximo de solicitações permitidas por hora para cada usuário individual.';
$string['verified'] = 'Verificado';
$string['webserviceconfig_current'] = 'Configuração atual';
$string['webserviceconfig_desc'] = 'Configura automaticamente um serviço web dedicado para o serviço de IA Datacurso, permitindo que ele extraia com segurança informações da plataforma, como dados básicos de usuários, cursos e atividades para melhor contextualização da IA. Esta configuração cria um usuário de serviço, atribui o papel necessário, configura o serviço externo, gera um token seguro e habilita o protocolo REST em um clique. Nota: O valor do token não é exibido por razões de segurança.';
$string['webserviceconfig_heading'] = 'Configuração automática de serviço web';
$string['webserviceconfig_site'] = 'Informações do site';
$string['webserviceconfig_status'] = 'Status';
$string['webserviceconfig_title'] = 'Configuração de serviço web Datacurso';
$string['workplace'] = 'Este é o Moodle Workplace?';
$string['workplace_desc'] = 'Define se o cabeçalho X-Workplace deve ser enviado com o valor true (Workplace) ou false (Moodle padrão).';
$string['ws_activity'] = 'Log de atividade';
$string['ws_btn_regenerate'] = 'Regenerar token';
$string['ws_btn_retry'] = 'Tentar configuração novamente';
$string['ws_btn_setup'] = 'Configurar serviço web';
$string['ws_enabled'] = 'Serviços web habilitados';
$string['ws_error_missing_setup'] = 'Serviço ou usuário não encontrado. Execute a configuração primeiro.';
$string['ws_error_missing_token'] = 'Token não encontrado. Gere-o primeiro.';
$string['ws_error_regenerate_token'] = 'Erro ao regenerar o token.';
$string['ws_error_registration'] = 'Erro ao registrar o token do serviço web.';
$string['ws_error_setup'] = 'Erro ao configurar o serviço web.';
$string['ws_role'] = 'Papel do serviço';
$string['ws_service'] = 'Serviço externo';
$string['ws_step_enableauth'] = 'Habilitando plugin de autenticação de serviços web…';
$string['ws_step_enablerest'] = 'Habilitando protocolo REST…';
$string['ws_step_enablews'] = 'Habilitando serviços web do site…';
$string['ws_step_registration_sent'] = 'Solicitação de registro enviada.';
$string['ws_step_role_assign'] = 'Atribuindo papel ao usuário do serviço…';
$string['ws_step_role_caps'] = 'Definindo capacidades de papel necessárias…';
$string['ws_step_role_create'] = 'Criando papel "{$a}"…';
$string['ws_step_role_exists'] = 'O papel já existe, usando ID {$a}…';
$string['ws_step_service_enable'] = 'Criando/Habilitando serviço externo…';
$string['ws_step_service_functions'] = 'Adicionando funções comuns do núcleo ao serviço…';
$string['ws_step_service_user'] = 'Autorizando usuário para o serviço…';
$string['ws_step_setup'] = 'Iniciando configuração…';
$string['ws_step_token_create'] = 'Garantindo que o token exista…';
$string['ws_step_token_generated'] = 'Token gerado.';
$string['ws_step_token_regenerated'] = 'Token regenerado.';
$string['ws_step_token_regenerating'] = 'Regenerando token…';
$string['ws_step_token_retry'] = 'Tentando configuração novamente…';
$string['ws_step_user_check'] = 'Verificando se o usuário "{$a}" existe…';
$string['ws_step_user_create'] = 'Criando usuário do serviço "{$a}"…';
$string['ws_tokenexists'] = 'Token existe';
$string['ws_user'] = 'Usuário do serviço';
$string['ws_userassigned'] = 'Papel atribuído ao usuário';
