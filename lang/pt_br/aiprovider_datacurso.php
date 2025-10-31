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
$string['action:generate_text:instruction_desc'] = 'Esta instrução é enviada ao modelo de IA junto com o prompt do usuário. Não é recomendado editar esta instrução, a menos que seja absolutamente necessário.';
$string['action:summarise_text:endpoint'] = 'Endpoint da API';
$string['action:summarise_text:endpoint_desc'] = 'O endpoint para gerar texto';
$string['action:summarise_text:instruction'] = 'Instrução do sistema';
$string['action:summarise_text:instruction_desc'] = 'Esta instrução é enviada ao modelo de IA junto com o prompt do usuário. Não é recomendado editar esta instrução, a menos que seja absolutamente necessário.';
$string['all'] = 'Todos';
$string['apikey'] = 'Chave da API';
$string['apikey_desc'] = 'Insira a chave da API do seu serviço Datacurso para conectar a IA.';
$string['apiurl'] = 'URL base da API';
$string['apiurl_desc'] = 'Insira a URL base do serviço para conectar à API Datacurso.';
$string['assigned'] = 'Atribuído';
$string['chart_actions'] = 'Distribuição de créditos por serviço';
$string['chart_tokens_by_day'] = 'Consumo de créditos por dia';
$string['chart_tokens_by_month'] = 'Número de créditos consumidos por mês';
$string['configured'] = 'Configurado';
$string['contextwstoken'] = 'Token de serviço web para contexto do curso';
$string['contextwstoken_desc'] = 'Token usado pela IA para recuperar informações do curso (contexto). Armazenado com segurança. Criar/gerenciar tokens em Administração do site > Servidor > Serviços web > Gerenciar tokens.';
$string['created'] = 'Criado';
$string['curlerror'] = 'Erro cURL da API Datacurso: {$a}';
$string['datacurso:manage'] = 'Gerenciar configurações do provedor de IA';
$string['datacurso:use'] = 'Usar serviços de IA Datacurso';
$string['datacurso:viewreports'] = 'Ver relatórios de uso de IA';
$string['description'] = 'Descrição';
$string['descriptionpagelistplugins'] = 'Aqui você pode encontrar a lista de plugins compatíveis com o provedor Datacurso';
$string['disabled'] = 'Desabilitado';
$string['emptyprompt'] = 'Prompt vazio';
$string['emptyresponse'] = 'Sem resposta da API Datacurso.';
$string['enabled'] = 'Habilitado';
$string['enableglobalratelimit'] = 'Habilitar limite global';
$string['enableglobalratelimit_desc'] = 'Se habilitado, um limite de solicitações global por hora será aplicado para todos os usuários.';
$string['enableuserratelimit'] = 'Habilitar limite por usuário';
$string['enableuserratelimit_desc'] = 'Se habilitado, cada usuário terá um limite de solicitações por hora.';
$string['errorgetbalancecredits'] = 'Não foi possível recuperar o saldo de créditos da API externa';
$string['errorinitinformation'] = 'As informações iniciais não puderam ser obtidas.';
$string['exists'] = 'Existe';
$string['forbidden'] = 'Você não tem permissão para executar esta ação com a licença atual. Por favor, verifique sua licença e créditos disponíveis em <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gerenciar Créditos</a> na Loja Datacurso.';
$string['generate_activitie'] = 'Gerar atividade ou recurso com IA';
$string['generate_ai_reinforcement_activity'] = 'Criar atividade de reforço com IA';
$string['generate_analysis_comments'] = 'Gerar análise de avaliação de uma atividade/recurso com IA';
$string['generate_analysis_course'] = 'Gerar análise de avaliação do curso com IA';
$string['generate_analysis_general'] = 'Gerar análise de avaliação geral com IA';
$string['generate_analysis_story_student'] = 'Gerar análise da história do aluno com IA';
$string['generate_assign_answer'] = 'Gerar revisão de tarefa com IA';
$string['generate_certificate_answer'] = 'Gerar mensagem de certificado com IA';
$string['generate_creation_course'] = 'Criar curso completo com IA';
$string['generate_forum_chat'] = 'Gerar resposta de fórum com IA';
$string['generate_image'] = 'Gerar imagem com IA';
$string['generate_plan_course'] = 'Gerar plano de criação de curso com IA';
$string['generate_summary'] = 'Gerar resumo com IA';
$string['generate_text'] = 'Gerar texto com IA';
$string['globalratelimit'] = 'Limite de solicitações global';
$string['globalratelimit_desc'] = 'Número máximo de solicitações permitidas por hora para todo o sistema.';
$string['goto'] = 'Ir para o Relatório';
$string['gotopage'] = 'Ir para a página';
$string['httperror'] = 'Erro inesperado ao processar sua solicitação (HTTP {$a}). Por favor, tente novamente mais tarde. Se o problema persistir, entre em contato com o administrador do site.';
$string['id'] = 'ID';
$string['installed'] = 'Instalado';
$string['invalidlicensekey'] = 'A chave de licença expirou ou não é válida. Por favor, acesse <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gerenciar Créditos</a> na Loja Datacurso para renovar ou comprar uma nova licença.';
$string['json_encode_failed'] = 'Falha na codificação JSON';
$string['jsondecodeerror'] = 'Erro ao processar resposta da API Datacurso: {$a}';
$string['last_sent'] = 'Último envio';
$string['license_not_allowed'] = 'Sua licença não permite executar esta solicitação. Por favor, gerencie suas licenças e créditos em <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gerenciar Créditos</a> na Loja Datacurso.';
$string['licensekey'] = 'Chave de licença';
$string['licensekey_desc'] = 'Insira a chave de licença da área de clientes da Loja Datacurso.';
$string['link_consumptionhistory'] = 'Histórico de consumo de créditos';
$string['link_generalreport'] = 'Relatório geral';
$string['link_generalreport_datacurso'] = 'Relatório geral Datacurso IA';
$string['link_listplugings'] = 'Lista de plugins Datacurso';
$string['link_plugin'] = 'Link';
$string['link_report_statistic'] = 'Relatório de estatísticas gerais';
$string['link_webservice_config'] = 'Configuração de serviço web Datacurso';
$string['live_log'] = 'Log ao vivo';
$string['message_no_there_plugins'] = 'Nenhum plugin disponível';
$string['missing'] = 'Ausente';
$string['needs_repair'] = 'Precisa de reparo';
$string['nodata'] = 'Nenhuma informação encontrada';
$string['not_assigned'] = 'Não atribuído';
$string['not_configured'] = 'Não configurado';
$string['not_created'] = 'Não criado';
$string['notenoughtokens'] = 'Créditos de IA insuficientes. Por favor, visite <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gerenciar Créditos</a> na Loja Datacurso para alocar ou comprar mais créditos. Ou entre em contato com seu administrador.';
$string['of'] = 'de';
$string['orgid'] = 'ID da organização';
$string['orgid_desc'] = 'Insira o identificador da sua organização no serviço Datacurso.';
$string['pageinfo'] = 'Página {$a->current} de {$a->totalpages} ({$a->total} registros)';
$string['pending'] = 'Pendente';
$string['plugin'] = 'Plugin';
$string['plugindesc_assign_ai'] = 'Revisar tarefas com assistência de IA.';
$string['plugindesc_coursegen'] = 'Criar cursos completos, atividades e recursos com IA.';
$string['plugindesc_datacurso_ratings'] = 'Permite que os alunos avaliem atividades e recursos; professores e administradores podem gerar análises de cursos baseadas em IA.';
$string['plugindesc_dttutor'] = 'Conversar com um tutor de IA dentro do curso.';
$string['plugindesc_forum_ai'] = 'Estender fóruns com análise alimentada por IA para gerar resumos automaticamente.';
$string['plugindesc_lifestory'] = 'Relatório e análise alimentados por IA do progresso acadêmico do aluno.';
$string['plugindesc_smartrules'] = 'Criar atividades automatizadas com base em condições anteriores dos alunos.';
$string['plugindesc_socialcert'] = 'Gerar automaticamente certificados personalizados ao concluir o curso.';
$string['pluginname'] = 'Provedor de IA Datacurso';
$string['pluginname_assign_ai'] = 'Tarefa IA';
$string['pluginname_coursegen'] = 'Criador de Curso IA';
$string['pluginname_datacurso_ratings'] = 'Classificação de Atividades IA';
$string['pluginname_dttutor'] = 'Tutor IA';
$string['pluginname_forum_ai'] = 'Fórum IA';
$string['pluginname_lifestory'] = 'História de Vida do Aluno IA';
$string['pluginname_smartrules'] = 'SmartRules IA';
$string['pluginname_socialcert'] = 'Compartilhar Certificado IA';
$string['privacy:metadata'] = 'O plugin Provedor de IA Datacurso não armazena nenhum dado pessoal localmente. Todos os dados são processados pelos serviços externos de IA Datacurso.';
$string['privacy:metadata:aiprovider_datacurso'] = 'Cargas úteis de solicitações de IA Datacurso enviadas ao serviço externo.';
$string['privacy:metadata:aiprovider_datacurso:externalpurpose'] = 'Estes dados são enviados para Datacurso IA para cumprir a ação solicitada.';
$string['privacy:metadata:aiprovider_datacurso:numberimages'] = 'Número total de imagens solicitadas do serviço de IA.';
$string['privacy:metadata:aiprovider_datacurso:prompt'] = 'O texto do prompt fornecido ao serviço de IA.';
$string['privacy:metadata:aiprovider_datacurso:userid'] = 'O ID do usuário Moodle fazendo a solicitação de IA.';
$string['read_context_course'] = 'Ler contexto para criação de curso com IA';
$string['read_context_course_model'] = 'Carregar modelo acadêmico para criação de curso com IA';
$string['registers'] = 'Registros';
$string['registration_error'] = 'Último erro';
$string['registration_last'] = 'Registro';
$string['registration_lastsent'] = 'Último envio';
$string['registration_notverified'] = 'Registro não verificado';
$string['registration_status'] = 'Último status';
$string['registration_verified'] = 'Registro verificado';
$string['registrationapibearer'] = 'Token bearer de registro';
$string['registrationapibearer_desc'] = 'Token bearer usado para autenticar a solicitação de registro.';
$string['registrationapiurl'] = 'URL do endpoint de registro';
$string['registrationapiurl_desc'] = 'Endpoint para receber a carga útil de registro do site. Padrão: http://localhost:8001/register-site';
$string['registrationsettings'] = 'API de registro';
$string['remainingtokens'] = 'Saldo restante';
$string['responseinvalidai'] = 'Resposta inválida do serviço de IA.';
$string['responseinvalidaimage'] = 'Resposta inválida do serviço de IA (sem imagem).';
$string['responseinvalidaimagecreate'] = 'Não foi possível criar o arquivo de imagem.';
$string['rest_enabled'] = 'Protocolo REST habilitado';
$string['service'] = 'Serviço';
$string['showrows'] = 'Mostrar linhas';
$string['tokens'] = 'Créditos';
$string['tokens_available'] = 'Créditos disponíveis';
$string['tokensconsumed'] = 'Créditos consumidos';
$string['tokensconsumedday'] = 'Créditos consumidos por dia';
$string['tokensconsumedmonth'] = 'Créditos consumidos por mês';
$string['tokensnotsufficient'] = 'Créditos de IA insuficientes. Saldo atual: {$a->current}. Mínimo necessário: {$a->required}. Por favor, visite <a href="https://shop.datacurso.com/index.php?m=tokens_manager" target="_blank">Gerenciar Créditos</a> na Loja Datacurso para alocar ou comprar mais créditos. Ou entre em contato com seu administrador.';
$string['tokensused'] = 'Créditos usados';
$string['tokenthreshold'] = 'Limite de créditos';
$string['tokenthreshold_desc'] = 'Número de créditos a partir do qual uma notificação será exibida para comprar mais.';
$string['total_consumed'] = 'Créditos consumidos';
$string['userid'] = 'Usuário';
$string['userratelimit'] = 'Limite de solicitações por usuário';
$string['userratelimit_desc'] = 'Número máximo de solicitações permitidas por hora para cada usuário individual.';
$string['verified'] = 'Verificado';
$string['webserviceconfig_current'] = 'Configuração atual';
$string['webserviceconfig_desc'] = 'Configura automaticamente um serviço web dedicado para o serviço de IA Datacurso, permitindo que ele extraia com segurança informações da plataforma, como dados básicos de usuários, cursos e atividades para melhor contextualização da IA. Esta configuração cria um usuário de serviço, atribui a função necessária, configura o serviço externo, gera um token seguro e habilita o protocolo REST com um clique. Nota: O valor do token não é exibido por razões de segurança.';
$string['webserviceconfig_heading'] = 'Configuração automática de serviço web';
$string['webserviceconfig_site'] = 'Informações do site';
$string['webserviceconfig_status'] = 'Status';
$string['webserviceconfig_title'] = 'Configuração de Serviços Web Datacurso';
$string['workplace'] = 'Este é o Moodle Workplace?';
$string['workplace_desc'] = 'Define se o cabeçalho X-Workplace deve ser enviado com valor true (Workplace) ou false (Moodle Padrão).';
$string['ws_activity'] = 'Log de atividade';
$string['ws_btn_regenerate'] = 'Regenerar token';
$string['ws_btn_retry'] = 'Tentar novamente a configuração';
$string['ws_btn_setup'] = 'Configurar serviço web';
$string['ws_enabled'] = 'Serviços web habilitados';
$string['ws_error_missing_setup'] = 'Serviço ou usuário não encontrado. Execute a configuração primeiro.';
$string['ws_error_missing_token'] = 'Token não encontrado. Gere-o primeiro.';
$string['ws_error_regenerate_token'] = 'Erro ao regenerar o token.';
$string['ws_error_registration'] = 'Erro ao registrar o token do serviço web.';
$string['ws_error_setup'] = 'Erro ao configurar o serviço web.';
$string['ws_role'] = 'Função do serviço';
$string['ws_service'] = 'Serviço externo';
$string['ws_step_enableauth'] = 'Habilitando plugin de autenticação de serviços web…';
$string['ws_step_enablerest'] = 'Habilitando protocolo REST…';
$string['ws_step_enablews'] = 'Habilitando serviços web do site…';
$string['ws_step_registration_sent'] = 'Solicitação de registro enviada.';
$string['ws_step_role_assign'] = 'Atribuindo função ao usuário do serviço…';
$string['ws_step_role_caps'] = 'Definindo capacidades de função necessárias…';
$string['ws_step_role_create'] = 'Criando função "{$a}"…';
$string['ws_step_role_exists'] = 'A função já existe, usando ID {$a}…';
$string['ws_step_service_enable'] = 'Criando/Habilitando serviço externo…';
$string['ws_step_service_functions'] = 'Adicionando funções principais comuns ao serviço…';
$string['ws_step_service_user'] = 'Autorizando usuário para o serviço…';
$string['ws_step_setup'] = 'Iniciando configuração…';
$string['ws_step_token_create'] = 'Garantindo que o token existe…';
$string['ws_step_token_generated'] = 'Token gerado.';
$string['ws_step_token_regenerated'] = 'Token regenerado.';
$string['ws_step_token_regenerating'] = 'Regenerando token…';
$string['ws_step_token_retry'] = 'Tentando novamente a configuração…';
$string['ws_step_user_check'] = 'Verificando se o usuário "{$a}" existe…';
$string['ws_step_user_create'] = 'Criando usuário do serviço "{$a}"…';
$string['ws_tokenexists'] = 'Token existe';
$string['ws_user'] = 'Usuário do serviço';
$string['ws_userassigned'] = 'Função atribuída ao usuário';
