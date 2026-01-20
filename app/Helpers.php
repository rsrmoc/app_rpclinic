<?php

use App\Bibliotecas\ApiWaMe;
use App\Bibliotecas\Kentro;
use App\Model\rpclinica\Agendamento;
use App\Model\rpclinica\Paciente;
use App\Model\rpclinica\AgendamentoLog;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Bibliotecas\PDFSign;
use App\Model\rpclinica\Certificado;
use App\Model\rpclinica\Exame;
use App\Model\rpclinica\Menu;
use App\Model\rpclinica\Oft_formularios_imagens;
use App\Model\rpclinica\ProcedimentoConvenio;
use App\Model\rpclinica\Profissional;
use App\Model\rpclinica\Rota;
use App\Model\rpclinica\Usuario;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

const ESTADOS = [
    ["nome" => "Acre", "sigla" => "AC"],
    ["nome" => "Alagoas", "sigla" => "AL"],
    ["nome" => "Amapá", "sigla" => "AP"],
    ["nome" => "Amazonas", "sigla" => "AM"],
    ["nome" => "Bahia", "sigla" => "BA"],
    ["nome" => "Ceará", "sigla" => "CE"],
    ["nome" => "Distrito Federal", "sigla" => "DF"],
    ["nome" => "Espírito Santo", "sigla" => "ES"],
    ["nome" => "Goiás", "sigla" => "GO"],
    ["nome" => "Maranhão", "sigla" => "MA"],
    ["nome" => "Mato Grosso", "sigla" => "MT"],
    ["nome" => "Mato Grosso do Sul", "sigla" => "MS"],
    ["nome" => "Minas Gerais", "sigla" => "MG"],
    ["nome" => "Pará", "sigla" => "PA"],
    ["nome" => "Paraíba", "sigla" => "PB"],
    ["nome" => "Paraná", "sigla" => "PR"],
    ["nome" => "Pernambuco", "sigla" => "PE"],
    ["nome" => "Piauí", "sigla" => "PI"],
    ["nome" => "Rio de Janeiro", "sigla" => "RJ"],
    ["nome" => "Rio Grande do Norte", "sigla" => "RN"],
    ["nome" => "Rio Grande do Sul", "sigla" => "RS"],
    ["nome" => "Rondônia", "sigla" => "RO"],
    ["nome" => "Roraima", "sigla" => "RR"],
    ["nome" => "Santa Catarina", "sigla" => "SC"],
    ["nome" => "São Paulo", "sigla" => "SP"],
    ["nome" => "Sergipe", "sigla" => "SE"],
    ["nome" => "Tocantins", "sigla" => "TO"]
];

const TIPOS_CONVENIO = [
    '' => 'Não Informado',
    'CO' => 'CONVENIO',
    'SUS' => 'SUS',
    'PA' => 'PARTICULAR'
];

const TIPO_SEXO = [
    '' => 'Não Informado',
    'H' => 'Masculino',
    'F' => 'Feminino',
    'M' => 'Feminino'
];

const DIA_DA_SEMANA = [
    '0' => 'DOMINGO',
    '1' => 'SEGUNDA',
    '2' => 'TERÇA',
    '3' => 'QUARTA',
    '4' => 'QUINTA',
    '5' => 'SEXTA',
    '6' => 'SÁBADO',
    '' => 'Não Informado'
];

const MES_ANO = [
    '01' => 'Janeiro',
    '02' => 'Fevereiro',
    '03' => 'Março',
    '04' => 'Abril',
    '05' => 'Maio',
    '06' => 'Junho',
    '07' => 'Julho',
    '08' => 'Agosto',
    '09' => 'Setembro',
    '10' => 'Outubro',
    '11' => 'Novembro',
    '12' => 'Dezembro',
    ''   => '' 
];

const CODIGO_DIA_DA_SEMANA = [
    '0' => 'domingo',
    '1' => 'segunda',
    '2' => 'terca',
    '3' => 'quarta',
    '4' => 'quinta',
    '5' => 'sexta',
    '6' => 'sabado'
];

const SN_ATIVO = [
    '' => 'Não Informado',
    null => 'Não Informado',
    'S' => 'Ativo',
    'N' => 'Inativo'
];

const CSS_SN_ATIVO = [
    '' => 'Não Informado',
    'S' => 'label label-success',
    'N' => 'label label-danger'
];

const CSS_DESP_RECEI = [
    'RECEITA' => 'label label-success',
    'DESPESA' => 'label label-danger',
    '' => ''
];

const CLASSIF_XYZ = [
    'X' => 'Cuidado Rígido',
    'Y' => 'Cuidado Normal',
    'Z' => 'Moderado'
];


// CATEGORIA
const EXTRUTURAL_CATEGORIA = [
    [
        'COD' => 1,
        'DESCRICAO' => 'RECEITA PACIENTE',
        'OPERACAO' => 'RECEITA'
    ],
    [
        'COD' => 1.1,
        'DESCRICAO' => 'RECEITA PARTICULAR',
        'OPERACAO' => 'RECEITA'
    ],
    [
        'COD' => 1.2,
        'DESCRICAO' => 'RECEITA CONVENIO',
        'OPERACAO' => 'RECEITA'
    ],
    [
        'COD' => '1.2.1',
        'DESCRICAO' => 'RECEITA CONVENIO UNIMED',
        'OPERACAO' => 'RECEITA'
    ],
    [
        'COD' => 2,
        'DESCRICAO' => 'DESPESAS GERAL',
        'OPERACAO' => 'DESPESA'
    ],
    [
        'COD' => 2.1,
        'DESCRICAO' => 'DESPESAS PACIENTE',
        'OPERACAO' => 'DESPESA'
    ],
    [
        'COD' => 2.2,
        'DESCRICAO' => 'DESPESAS EXAMES',
        'OPERACAO' => 'DESPESA'
    ],
];
// FIM CATEGORIA


// ROTAS
const OPCOES_ROTAS = [
    'tabelas_consultorio' => [
        "procedimento",
        "grupo",
        "especialidade",
        "convenio",
        "local",
        "tipo",
        "feriados",
        "profissional",
        "formulario",
        "agenda"
    ],
    'tabelas_financeiro' => [
        "conta",
        "cartao",
        "forma",
        "marca",
        "config"
    ],
    'tabelas_estoque' => [
        "classificacao",
        "produto",
        "tab-estoque",
        "tipoaj",
        "motivo"
    ],
    'perfil-profi',
    'categoria',
    'empresa',
    'setor',
    'comunicacao',
    'fornecedor',

    'modulo_agendamento' => [
        "agendamentos",
        "recepcao",
        "tesouraria",
    ],
    'modulo_consultorio' => [
        "consultorio",
        "reserva-cirurgia",
        "central-laudos",
    ],
    'modulo_faturamento' => [
        "faturamento", 
    ],
    'modulo_financeiro' => [
        "financeiro",  
    ], 
    'tabelas_comunicacao',
    'tabelas_empresa',
    'tabelas_setor',
    'tabelas_categoria',
    'tabelas_fornecedor',
    'paciente',
    'agendamento',
    'consulta',
    'consultorio',
    "consultorio_oftalmologia",
    'faturamento' => ['faturamentos'],
    'financeiro' => [],
    'estoque' => [
        'entrada',
        'saida',
        'devolucao',
        'ajuste',
        'saldo'
    ],
    'relatorios'
];
 

const OPCOES_TIPO_CONTAS = [
    '' => '...',
    'AP' => 'APLICAÇÃO',
    'CG' => 'CONTA GARANTIDA',
    'CA' => 'CARTÃO',
    'CC' => 'CONTA CORRENTE',
    'CR' => 'CORRETORA',
    'CX' => 'CAIXA FISICO',
    'OU' => 'OUTROS'
];

const OPCOES_ESTADOS_CIVIS = [
    '' => 'Não Informado',
    'S' => 'Solteiro',
    'C' => 'Casado',
    'D' => 'Divorciado',
    'V' => 'Viúvo',
    ''  => 'Não Informado'
];

const CLASS_CORES_SITUACAO_AGENDAMENTO = [
    'livre' => 'label-success',
    'agendado' => 'label-primary',
    'confirmado' => 'label-info',
    'atendido' => 'label-warning',
    'bloqueado' => 'label-danger',
    'cancelado' => 'label-danger',
    'aguardando' => 'label-aguardando',
    'atendimento' => 'label-aguardando'
];

function cssRouteGrupoMenu($Grupo) { 
  
    $Set = Menu::where('grupo',$Grupo)->where('cd_opcao_menu',Route::currentRouteName())->count();
    return ($Set == 1) ? 'active' : '';
}

function cssRouteSubGrupoMenu($SubGrupo) { 
    
    $Set = Menu::where('sub_grupo',$SubGrupo)->where('cd_opcao_menu',Route::currentRouteName())->count();
    return ($Set == 1) ? 'active droplink open' : '';
}

function cssRouteMenu($Grupo) { 
    $Set = Menu::where('cd_opcao_menu',Route::currentRouteName())->selectRaw("distinct(controller) controller")->first();
    if(isset($Set->controller)) $controller = $Set->controller; else $controller=null;
    return ($controller == $Grupo) ? 'active active2' : '';
}

function formartMoedaBD($valor) {
    if($valor){
        $valor = str_replace('.', '', $valor); 
        $valor = str_replace(',', '.', $valor); 
    }else{
        $valor = null;
    }

    return $valor;
}

function formartMoedaCAMPO($valor) {
    $valor = str_replace('.', ',', $valor); 
    return $valor;
}

function routeMenu($tp) {

}

function currentNameRouteNoDots() {
    
    return substr(Route::currentRouteName(), 0, strpos(Route::currentRouteName(), ".") === false ? strlen(Route::currentRouteName()) : strpos(Route::currentRouteName(), "."));
}

function chavesOpcoesRotas() {
    return array_map(fn($chave, $valor) => is_array($valor) ? $chave : $valor, array_keys(OPCOES_ROTAS), array_values(OPCOES_ROTAS));
}

function cssRouteCurrent($nome) {

    return str_contains(Route::currentRouteName(), $nome) ? 'active': '';
}

function cssCategRouteCurrent($categoria) { 
   
    return in_array(currentNameRouteNoDots(), OPCOES_ROTAS[$categoria]);

}
 
function isTabelaRotas() {
  
    foreach (array_slice(OPCOES_ROTAS, 0, 9) as $rota) {
        if (is_array($rota)) {
            if (in_array(currentNameRouteNoDots(), $rota)) return true;
            continue;
        }

        if (str_contains($rota, currentNameRouteNoDots())) return true;
    }

    return false;
}

function isModulosRotas($item) {
  
    foreach (OPCOES_ROTAS[$item] as $rota) {
        if (is_array($rota)) {
            if (in_array(currentNameRouteNoDots(), $rota)) return true;
            continue;
        }

        if (str_contains($rota, currentNameRouteNoDots())) return true;
    }

    return false;
}



// FIM ROTAS


function formatCurrencyForDB($currency) {
    $currency = str_replace('.', '', $currency);
    $currency = str_replace(',', '.', $currency);

    return $currency ? $currency : 0;
}

function formatCurrencyForFront($currency) {
    $currency = str_replace('.', ',', $currency);
    return $currency ? $currency : 0;
}

function formatDateTime($currency) { 
    return date_format(date_create($currency), 'd/m/Y H:i');
}

function formatDate($currency) { 
    return date_format(date_create($currency), 'd/m/Y');
}

function formatPrimeiraLetraMaiuscula($dados) { 
    return mb_convert_case($dados, MB_CASE_TITLE, "UTF-8");
}

function camposInteligentes(string $conteudo, Paciente $paciente, Agendamento $agendamento) {
    if(empty($conteudo)){ $conteudo = "<p>&nbsp;</p>"; }
    return str_replace([
        "@CodPaciente",
        "@PacienteNome",
        "@PacienteIdade",
        "@PacienteNascimento",
        "@PacienteMae",
        "@PacientePai",
        "@PacienteCpf",
        "@PacienteRG",
        "@PacienteCivil",
        "@PacienteTelefone",
        "@PacienteCelular",
        "@PacienteEmail",
        "@PacienteEnd",
        "@PacienteEndNum",
        "@PacienteBairro",
        "@PacienteCidade",
        "@PacienteUF",
        "@PacienteCep",
        "@Profissional_nome",
        "@Profissional_conselho",
        "@Atendimento",
        "@AtendimentoData",
        "@Nome_Profissional",
        "@NmLaudante_medico",
        "@CRM",
        "@Medico",
        "@Data_Sem_Hora",
        " "
    ],
    [
        $paciente->cd_paciente,
        $paciente->nm_paciente,
        ($paciente->dt_nasc) ? idadeResumido($paciente->dt_nasc) : null,
        date_format(date_create($paciente->dt_nasc), 'm/d/Y'),
        $paciente->nm_mae,
        $paciente->nm_pai,
        $paciente->cpf,
        $paciente->rg,
        $paciente->estado_civil,
        $paciente->fone,
        $paciente->celular,
        $paciente->email,
        $paciente->logradouro,
        $paciente->numero,
        $paciente->bairro,
        $paciente->cidade,
        $paciente->uf,
        $paciente->cep,
        $agendamento->profissional?->nm_profissional,
        $agendamento->profissional?->crm,
        $agendamento->cd_agendamento,
        date_format(date_create($agendamento->data_horario), 'm/d/Y \à\s H:i\h'),
        $paciente->nm_paciente,
        $agendamento->profissional?->nm_profissional,
        $agendamento->profissional?->crm,
        $agendamento->profissional?->nm_profissional,
        date('d/m/Y'),
        " "
    ],
    $conteudo);
}

function trocar_ponto_virgula($moeda) {
    $valor = preg_replace('/[^0-9]/', '', $moeda);    
    $valor = bcdiv($valor, 100, 2);
    $valor = strtr($valor, ',', '.');
    return $valor;
}


function camposInteligentesPac(string $conteudo, $paciente, $profissional) {
    if(empty($conteudo)){ $conteudo = "<p>&nbsp;</p>"; }
    return str_replace([
        "[paciente]",
        "[paciente_nome]",
        "[paciente_idade]",
        "[paciente_nascimento]",
        "[paciente_mae]",
        "[paciente_pai]",
        "[paciente_cpf]",
        "[paciente_rg]",
        "[paciente_civil]",
        "[telefone]",
        "[celular]",
        "[email]",
        "[paciente_end]",
        "[paciente_end_num]",
        "[paciente_bairro]",
        "[paciente_cidade]",
        "[paciente_uf]",
        "[paciente_cep]",
        "[profissional_nome]",
        "[profissional_conselho]",
        "[atendimento]",
        "[atendimento_data]",
        " "
    ],
    [
        $paciente->cd_paciente,
        $paciente->nm_paciente,
        ($paciente->dt_nasc) ? idadeAluno($paciente->dt_nasc) : null,
        date_format(date_create($paciente->dt_nasc), 'm/d/Y'),
        $paciente->nm_mae,
        $paciente->nm_pai,
        $paciente->cpf,
        $paciente->rg,
        $paciente->estado_civil,
        $paciente->fone,
        $paciente->celular,
        $paciente->email,
        $paciente->logradouro,
        $paciente->numero,
        $paciente->bairro,
        $paciente->cidade,
        $paciente->uf,
        $paciente->cep,
        $profissional->nm_profissional,
        $profissional->crm, 
        " ",
        date_format(date_create(date('Y-m-d H:i')), 'm/d/Y \à\s H:i\h'),
        " "
    ],
    $conteudo);
}

function HelperValidaCPF($cpf) {
 
    // Extrai somente os números
    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
     
    // Verifica se foi informado todos os digitos corretamente
    if (strlen($cpf) != 11) {
        return false;
    }

    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    // Faz o calculo para validar o CPF
    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;

}


/* WHATSAPP */

const DADOS_WHAST = [
    'server' => 'https://server.api-wa.me'
];

function whast_getDesconectar($key)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, DADOS_WHAST['server'] . "/instance/logout?key={$key}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,  null);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array() );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return $result;

}

function whast_getListGroup($key)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, DADOS_WHAST['server'] . "/group/list?key={$key}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,  null);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array() );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return $result;

}

function whast_getQrCodeHTML($key)
{

       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, DADOS_WHAST['server'] . "/instance/qrcode_base64?key={$key}");
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
       curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
       curl_setopt($ch, CURLOPT_POST, 1);
       curl_setopt($ch, CURLOPT_POSTFIELDS,  null);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array() );
       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
       $result = curl_exec($ch);
       if (curl_errno($ch)) {
           return curl_error($ch);
       }
       curl_close($ch);
       return $result;

}


function whast_getValidaNumero($key,$numero)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, DADOS_WHAST['server'] . "/actions/validateNumber?key={$key}&to={$numero}");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,  null);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json') );
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return $result;

}

function whast_formatPhone($phone)
{
    $phone = preg_replace('/[^0-9]/', '', $phone);

     if(strlen($phone)==11){
        if(substr($phone,2,1)==9){
            $phone = trim(substr($phone,0,2).substr($phone,3,10));
        }
     }
    return $phone;
}

function request_envio($dados)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, DADOS_WHAST['server'] . $dados['parth']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $dados['method']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,  $dados['body']);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $dados['header']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        return curl_error($ch);
    }
    curl_close($ch);
    return $result;
}

function whast_getSendAgenda($key,$body)
{
    $dados['header'] =  array();
    array_push($dados['header'], 'Content-Type: application/json');
    $dados['parth']  = "/message/button?key=".$key;
    $dados['method'] = "POST";
    $dados['body']   = json_encode($body);
    return request_envio($dados);
}


function whast_getSendAgenda2($key,$body)
{
    $dados['header'] =  array();
    array_push($dados['header'], 'Content-Type: application/json');
    $dados['parth']  = "/message/text?key=".$key;
    $dados['method'] = "POST";
    $dados['body']   = json_encode($body);
    return request_envio($dados);
}

function idadeAluno($dataNasci)
{
    if(trim($dataNasci)){

        $nasci = new DateTime($dataNasci);
        $agora = new DateTime("now");

        $idade = $nasci->diff($agora);
        $Anos =  ($idade->y>0) ? $idade->y . " anos, " : null;
        $Meses = ($idade->m>1) ? $idade->m . " meses" : $idade->m . " mês";
        $Dias = ($idade->y==0) ? ", ".$idade->d . " dias " : null;

        return  $Anos.$Meses.$Dias;

    }else{

        return  null;

    }

}

function idadeResumido($dataNasci)
{
    if(trim($dataNasci)){

        $nasci = new DateTime($dataNasci);
        $agora = new DateTime("now");

        $idade = $nasci->diff($agora);
        $Anos =  ($idade->y>0) ? $idade->y . " anos " : null; 

        return  $Anos;

    }else{

        return  null;

    }

}

function funcLogsAtendimentoHelpers( $cd_agendamento,$tipo,$dados =null)
{
    $Modulo=null;
    if(isset($dados['modulo'])){
        $Modulo=$dados['modulo'];
    }
    AgendamentoLog::create(
        array('cd_usuario'=>Auth::user()->cd_usuario,
              'tp_log'=>'AGENDAMENTO',
              'chave'=>$cd_agendamento,
              'dt_log'=>date('Y-m-d H:i'),
              'dados'=> $tipo,
              'modulo'=> $Modulo
            )
      );

}

function HelperIconesAgendamento($tipo){
    $icone = '';
    switch ($tipo) {
        case 'agendado':
            $icone = "<i class='fa fa-calendar-o btnAgendado' style='  padding-bottom: 5px; margin-right: 0px; padding: 0px;' ></i>";
            break;
        case 'confirmado': 
            $icone = '<i class="fa fa-check btnConfirmado" style="  padding-bottom: 5px; margin-right: 0px; padding: 0px;"></i>';
            break;
        case 'aguardando':
            $icone = '<span class="glyphicon glyphicon-time btnAguardando" aria-hidden="true" style="  padding-bottom: 5px; margin-right: 0px; padding: 0px;">';
            break;
        case 'atendido':
            $icone = '<i class="fa fa-stethoscope btnAtendido" style=" padding-bottom: 5px; margin-right: 0px; padding: 0px;"></i>';
            break;
        case 'atendimento':
            $icone = '<i class="fa fa-user-md btnAtendimento" style="  padding-bottom: 5px; margin-right: 0px; padding: 0px;"></i>';
            break;
        case 'cancelado':
            $icone = '<span aria-hidden="true" class="icon-close btnCancelado" style="  padding-bottom: 5px; margin-right: 0px;"></span>';
            break;
        case 'faltou':
            $icone = '<span aria-hidden="true" class="icon-user-unfollow btnFaltou" style=" padding-bottom: 5px; margin-right: 0px;"></span>';
            break; 
        case 'bloqueado':
            $icone = '<i class="fa fa-remove btnFaltou" style=" padding-bottom: 5px; margin-right: 0px;"></i>';
            break; 
        default:
            $icone = ''; 
            break; 
    }
    return $icone;
}

 

function PrimeiraLetraMaiuscula($str){
    $str = mb_convert_case($str, MB_CASE_UPPER, "UTF-8");
    $str = mb_convert_case($str, MB_CASE_TITLE, "UTF-8");
    return $str;
}

function calculoDatashora($dti,$dtf,$tp){

    $hora_um = $dti;
    $hora_dois = $dtf;
    $h =  strtotime($hora_um);
    $h2 = strtotime($hora_dois);

    $minutos = date("i", $h2);
    $segundos = date("s", $h2);
    $hora = date("H", $h2);

    $temp = strtotime("+$minutos minutes", $h);
    $temp = strtotime("+$segundos seconds", $temp);
    $temp = strtotime("+$hora hours", $temp);
    if($tp=='DH'){
        $nova_hora = date('Y-m-d H:i', $temp);
    }
    if($tp=='H'){
        $nova_hora = date('H:i', $temp);
    }
    if($tp=='D'){
        $nova_hora = date('Y-m-d', $temp);
    }

    return $nova_hora;

}

function certidicadoDigital($DADOS) {

    $certContent =  base64_decode($DADOS['certificado']->pfx);
    $certPass = $DADOS['senha'];
    openssl_pkcs12_read($certContent, $certiciates, $certPass);
  
    if(empty($certiciates)){
        $array['retorno']=false;
        $array['msg']='Senha do certificado Invalida!';
        $array['hash']=null;
        $array['conteudo']=null;

        return $array;
    }
    $infoSignature = [
        'Name' => $DADOS['profissional']->nm_profissional,
        'Location' => '--',
        'Reason' => 'Assinatura do documento',
        'ContactInfo' => $DADOS['profissional']->email
    ];
    $Doc=base_path().'\public\certificado\\'. $DADOS['doc'];
    $pdf = new PDFSign($DADOS['profissional']->nm_profissional, $DADOS['profissional']->doc);
     
    $numPages = $pdf->setSourceFile($Doc);


    for($i = 0; $i < $numPages; $i++) {
        
        $pdf->AddPage();

        $tplId = $pdf->importPage($i+1);

        $pdf->setSignature(
            signing_cert: $certiciates['cert'],
            private_key: $certiciates['pkey'],
            private_key_password: $certPass,
            info: $infoSignature
        );

        $pdf->useTemplate($tplId, 0, 0);
        $pdf->setSignatureAppearance(10,10,10,10,1);


            if($DADOS['especial']=='S'){

                $Assinatura = '
                <div style="text-align: center; line-height: 8px;" >
                <table width="100%" border="0" cellpadding="5" cellspacing="5">
                    <tr>
                        <td width="30%"><div align="center"></div></td>
                        <td width="26%"><div align="center"></div></td>
                        <td width="40%" style="  border: 1px solid #2b2f35;"><div align="center">
                        Documento assinado digitalmente <br> <b>'.mb_strtoupper($infoSignature['Name']).'</b> <br> Data :  '.date('d/m/Y H:i:s').' <br> Verifique em https://validar.iti.gov.br/
                        </div></td>
                        <td width="4%"><div align="center"></div></td>
                    </tr>
                </table>
                </div> ';

                $pdf->setXY(10, 50);
                $pdf->SetFont('',null,7);
                $pdf->WriteHTML($Assinatura);
            }else{

                $Assinatura = '
                <div style="text-align: center; line-height: 8px;" >
                <table width="100%" border="0" cellpadding="5" cellspacing="5">
                    <tr>
                        <td width="30%"><div align="center"></div></td>
                        <td width="40%" style="  border: 1px solid #2b2f35;"><div align="center">
                        Documento assinado digitalmente <br> <b>'.mb_strtoupper($infoSignature['Name']).'</b> <br> Data :  '.date('d/m/Y H:i:s').' <br> Verifique em https://validar.iti.gov.br/
                        </div></td>
                        <td width="30%"><div align="center"></div></td>
                    </tr>
                </table>
                </div> ';

                $pdf->setXY(10, 224);
                $pdf->SetFont('',null,7);
                $pdf->WriteHTML($Assinatura);
            }


    }
    $DocAss=base_path().'\public\certificado\\'. $DADOS['doc_ass'];
    $pdf->Output( $DocAss , "F");

    $PDF = base64_encode(file_get_contents($DocAss));
    unlink($DocAss);
    unlink($Doc);

    $CertPriv   = openssl_x509_parse(openssl_x509_read($certiciates['cert']));
    $array['retorno']=true;
    $array['msg']='Assinado com sucesso!';
    $array['hash']=$CertPriv['hash'];
    $array['conteudo']=$PDF;
    return $array;


}

function valorContaFaturamento($convenio,$procedimento) {

    return (isset(ProcedimentoConvenio::where('sn_ativo','S')->where('cd_convenio',$convenio)->where('cd_procedimento',$procedimento)->first()->valor)) 
            ? ProcedimentoConvenio::where('sn_ativo','S')->where('cd_convenio',$convenio)->where('cd_procedimento',$procedimento)->first()->valor 
            : null;

}

function helperInforInstance($empresa){
    if($empresa->api_whast=='api-wa.me'){
        
        if($empresa->key_whast){
            $api= new ApiWaMe(); 
            $retorno = $api->inforInstance($empresa->key_whast);
            //dd($retorno);
            if($retorno['retorno']==true){
 
                $Instance = json_decode($retorno['dados'], true);
                if (isset($Instance['instance']['phoneConnected'])) {
                    if ($Instance['instance']['phoneConnected'] == false ) {
                        return 'Olá! Gostaria de informar que a API do WhatsApp está atualmente desconectada!<br>Acesse o caminho a seguir: <strong>Tabelas / Comunicação.</strong>';
                    }
                }else{
                    return 'Olá! Gostaria de informar que a API do WhatsApp está atualmente desconectada!<br>Acesse o caminho a seguir: <strong>Tabelas / Comunicação.</strong>';
                }
            }
            
        }else{
            return 'Olá! Gostaria de informar que a API do WhatsApp está atualmente desconectada!<br>Acesse o caminho a seguir: <strong>Tabelas / Comunicação.</strong>';
        }
         
    }

    if($empresa->api_whast=='kentro'){

        if( ($empresa->key_whast) && (($empresa->fila_whast)) ){
            $api = new Kentro();
            $dados = $api->getQueueStatus(); 
            if ($dados['retorno'] == true) {
                $Instance = json_decode($dados['dados'], true);
                if (isset($Instance['enabled'])) {
                    if($Instance['enabled'] == false){
                        return 'Olá! Gostaria de informar que a API do WhatsApp está atualmente desconectada!<br>Acesse o caminho a seguir: <strong>Tabelas / Comunicação.</strong>';
                    }
                }else{
                    return 'Olá! Gostaria de informar que a API do WhatsApp está atualmente desconectada!<br>Acesse o caminho a seguir: <strong>Tabelas / Comunicação.</strong>';
                } 
            }
        }else{
            return 'Olá! Gostaria de informar que a API do WhatsApp está atualmente desconectada!<br>Acesse o caminho a seguir: <strong>Tabelas / Comunicação.</strong>';
        }

    }
}

function helperValorItem($item,$convenio){
    $item=Exame::find($item);
    $valor=null;
    if(isset($item)){
        
        if($item->cod_proc){
            $proc = ProcedimentoConvenio::selectRaw('max(dt_vigencia),valor')
            ->where('cd_convenio',$convenio)->where('cd_procedimento',$item->cod_proc)
            ->where('sn_ativo','S')->groupBy('valor')->first();
      
            if(isset($proc->valor)){
                if($proc->valor){
                    $valor=$proc->valor;
                }
            } 
        }
    }
    return $valor;
}

function HelperSessionUsusario($usuario){
    $codUsuario=$usuario; 
    $usuario =Usuario::where('email',$codUsuario)
    ->join('perfil_rota','perfil_rota.cd_perfil','usuarios.cd_perfil')
    ->join('rota','rota.grupo','perfil_rota.nm_rota')
    ->selectRaw("rota.rota,rota.menu,rota.sub_menu")
    ->get(); 
    $rotas=null;
    $sub_menus=null;
    $menus=null; 
    if(isset($usuario)){ 
            foreach($usuario as $rota){ 
              if($rota->rota)
                $rotas[$rota->rota]=$rota->rota;        
              if($rota->sub_menu)
                $sub_menus[$rota->sub_menu]=$rota->sub_menu;   
              if($rota->menu)
                $menus[$rota->menu]=$rota->menu; 
            }  
    } 
     

    Session::put('perfil', ($rotas) ? $rotas : array());
    Session::put('perfil_menu', ($menus) ? $menus : array());
    Session::put('perfil_sub_menu',  ($sub_menus) ? $sub_menus : array()); 
    $rotas=null;
    $rotas = Rota::where('controla_rota','N')->selectRaw("rota")->get();   
    if(($rotas)){ 
        if(count($rotas->toArray())>0){
            $rota = $rotas->toArray();   
            Session::put('nao_controla_rota', array_column($rota , "rota"));
        }else{
            Session::put('nao_controla_rota', array());
        } 
    }else{
        Session::put('nao_controla_rota', array());
    } 

    Session::put('setar_perfil','S');

      
}


function HelperDeletaLaudoTemp(){
    $path = "laudo/";
    $diretorio = dir($path); 
    while($arquivo = $diretorio -> read()){ 
        if(pathinfo($path.$arquivo, PATHINFO_EXTENSION)=='pdf')
            unlink($path.$arquivo); 
    }
    $diretorio -> close();
}

function HELPERcorrigiTexto($conteudo) {

    $conteudo=str_replace('<p><br />\r\n&nbsp;</p>', '', $conteudo);
    $conteudo=str_replace('<p><br />\r\n<br />\r\n', '<p>', $conteudo);
    $conteudo=str_replace('<p><br />', '<p>', $conteudo);
    $conteudo=str_replace('<p>\r\n&nbsp;</p>', '', $conteudo);
    $conteudo=str_replace('&nbsp;', '', $conteudo);
    $conteudo=str_replace('<br />', '', $conteudo);
    return $conteudo;
    
}

function HelperTextoFaltou($paciente,$profissional,$dt_agenda,$hr_agenda,$TextoPadrao){

    $variavel = array("@PACIENTE", "@PROFISSIONAL", "@DATA", "@HR_AGENDAMENTO");
    $valores = array(
        ucfirst($paciente),
        ucfirst($profissional),
        date("d/m/Y", strtotime($dt_agenda)),
        $hr_agenda 
    );
    return str_replace($variavel, $valores, $TextoPadrao);
    
}

function HelperTextoPesquisaSatisfacao($paciente,$profissional,$TextoPadrao){

    $variavel = array("@PACIENTE", "@PROFISSIONAL");
    $valores = array(
        ucfirst($paciente),
        ucfirst($profissional)  
    );
    return str_replace($variavel, $valores, $TextoPadrao);
    
}
 

function HelperTextoNiver($paciente,$TextoPadrao){

    $variavel = array("@NOME");
    $valores = array(  ucfirst($paciente) );
    return str_replace($variavel, $valores, $TextoPadrao);
    
}

 
const nomeCampoRelatorio = [
    'dt_atendimento' => 'Data de Atendimento',
    'cd_profissional' => 'Nome do Profissional',
    'cod_exame' => 'Nome do Exame',
    'atendido' => 'label-warning',
    'bloqueado' => 'label-danger',
    'cancelado' => 'label-danger',
    'aguardando' => 'label-aguardando',
    'atendimento' => 'label-aguardando'
];


function helperAssinaturaGigital($ARQ,$Profissional,$certPass,$ReceitaEspecial){
 
    try { 

        
        if(empty($Profissional)){ 
            return [ 'status' => false, 'conteudo' => 'Profissional não informado!']; 
        }
        if(empty($certPass)){ 
            return [ 'status' => false, 'conteudo' => 'Profissional não informado!']; 
        }
        if(empty($ARQ)){ 
            return [ 'status' => false, 'conteudo' => 'Arquivo não informado!']; 
        }
 
        $arquivoPDF='data:application/pdf;base64,'.base64_encode($ARQ);

        $Cert = Certificado::find($Profissional);
        $Prof = Profissional::find($Profissional); 

        if(!isset($Cert->pfx)){ 
            if(!$Cert->pfx){ 
                return [ 'status' => false, 'conteudo' => 'O certificado não esta configurado no sistema!']; 
            }
        }

        $certContent =  base64_decode($Cert->pfx); 
        openssl_pkcs12_read($certContent, $certiciates, $certPass);

        if(empty($certiciates)){
   
            return [ 'status' => false, 'conteudo' => 'Senha do certificado Invalida!' ];
                
        }else{

            $CertPriv   = openssl_x509_parse(openssl_x509_read($certiciates['cert'])); 
            if(isset($CertPriv['subject']['CN'])){
                $nm=$CertPriv['subject']['CN'];
            }else{
                $nm=$Cert->pfx_razao; 
            }
            $Nome=explode(':',$nm);
            $NomeAssinatura=$Nome[0];
    
            $infoSignature = [
                'Name' => $NomeAssinatura,
                'Location' => '--',
                'Reason' => 'Assinatura do documento',
                'ContactInfo' => $Prof->email
            ];

            $pdf = new PDFSign($NomeAssinatura, $Prof->doc); 
            $numPages = $pdf->setSourceFile($arquivoPDF);

            for($i = 0; $i < $numPages; $i++) {
            
                $pdf->AddPage();

                $tplId = $pdf->importPage($i+1);
        
                $pdf->setSignature(
                    signing_cert: $certiciates['cert'],
                    private_key: $certiciates['pkey'],
                    private_key_password: $certPass,
                    info: $infoSignature
                );
        
                $pdf->useTemplate($tplId, 0, 0);
                $pdf->setSignatureAppearance(10,10,10,10,1);

                if($ReceitaEspecial=='S'){ 
                    $Assinatura = '
                    <div style="text-align: center; line-height: 8px;" >
                    <table width="100%" border="0" cellpadding="5" cellspacing="5">
                        <tr>
                            <td width="30%"><div align="center"></div></td>
                            <td width="26%"><div align="center"></div></td>
                            <td width="40%" style="  border: 1px solid #2b2f35;"><div align="center">
                            Documento assinado digitalmente <br> <b>'.mb_strtoupper($infoSignature['Name']).'</b> <br> Data :  '.date('d/m/Y H:i:s').' <br> Verifique em https://validar.iti.gov.br/
                            </div></td>
                            <td width="4%"><div align="center"></div></td>
                        </tr>
                    </table>
                    </div> ';

                    $pdf->setXY(10, 50);
                    $pdf->SetFont('',null,7);
                    $pdf->WriteHTML($Assinatura); 

                }else{ 

                    $Assinatura = '
                    <div style="text-align: center; line-height: 8px;" >
                    <table width="100%" border="0" cellpadding="5" cellspacing="5">
                        <tr>
                            <td width="30%"><div align="center"></div></td>
                            <td width="40%" style="  border: 1px solid #2b2f35;"><div align="center">
                            Documento assinado digitalmente <br> <b>'.mb_strtoupper($infoSignature['Name']).'</b> <br> Data :  '.date('d/m/Y H:i:s').' <br> Verifique em https://validar.iti.gov.br/
                            </div></td>
                            <td width="30%"><div align="center"></div></td>
                        </tr>
                    </table>
                    </div> '; 
                    $pdf->setXY(10, 249);
                    $pdf->SetFont('',null,7);
                    $pdf->WriteHTML($Assinatura);

                }

            }

            return [ 'status' => true, 'conteudo' => base64_encode($pdf->Output('document.pdf','S')) ];

        }
    
                   
    } catch (Throwable $error) {
        
        return [ 'status'=>false, 'conteudo' => [$error->getMessage()]];

    }


}

function HELPERformatPhone($phone)
{
   $phone = preg_replace('/[^0-9]/', '', $phone);
 
    // Se tiver DDD mas nao tiver o 9 digito
    if (strlen($phone) == 10) { 
        $inicio = substr($phone, 0, 2);
        $fim =  substr($phone, 2, 10);
        $phone = $inicio.'9'.$fim; 
   };

   return $phone;
}

function HELPERformatTextoDocumento($texto)
{ 
     
    if(strpos( $texto, '<br />' ) == false){
        if(strpos( $texto, '<p>' ) == false){ return nl2br($texto); }  
        if(strpos( $texto, '</p>' ) == false){ return nl2br($texto); }  
    }
 
    return $texto;
}

function helperArray_img_geral($item)
{
    $dados['tab_img'] = Oft_formularios_imagens::where('cd_agendamento_item', $item)
        ->selectRaw("oft_formularios_imagens.*,date_format(created_at,'%d/%m/%Y') data")
        ->with('usuario')->orderBy('created_at', 'desc')->get(); 
    $dados['array_img']=null;
    foreach ($dados['tab_img'] as $imgs) {

        $CaminhoImg = null;
        $sn_caminho='N';
        clearstatcache(); // Limpamos o cache de arquivos do PHP

        if($imgs->sn_storage=='local'){
            $CaminhoPath=(env('URL_IMG_EXAMES', '/var/img_oftalmo'));
            $CaminhoImg = $CaminhoPath . "/" . $imgs->caminho_img; 
            if (is_file($CaminhoImg)) { 

                $mime_type = mime_content_type($CaminhoImg);   
                $ArrayImg['file_size'] = filesize($CaminhoImg); 
                $data = file_get_contents($CaminhoImg);
                $sn_caminho='S';
            } 
        }

        if($imgs->sn_storage=='s3'){
            $CaminhoImg = $imgs->caminho_img;
            if (Storage::disk('s3')->exists($CaminhoImg)) { 
    
                $mime_type = Storage::disk('s3')->mimeType($CaminhoImg); 
                $ArrayImg['file_size'] = Storage::disk('s3')->size($CaminhoImg); 
                $data = Storage::disk('s3')->get($CaminhoImg);
                $sn_caminho='S';
            }
        }

        if($sn_caminho=='S'){

            $ArrayImg['tipo'] = null;
            $ArrayImg['codigo'] = $imgs->cd_img_formulario;
            $ArrayImg['olho'] =  ($imgs->olho) ? $imgs->olho : ' -- '; 
            $ArrayImg['descricao'] =  ($imgs->descricao) ? $imgs->descricao : ' ... '; 
            $ArrayImg['sn_storage'] =  ($imgs->sn_storage) ? $imgs->sn_storage : ' ?? '; 
            $ArrayImg['mime_type'] = $mime_type; 

            if($ArrayImg['file_size']<=8388608){     
                $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
                $ArrayImg['sn_visualiza'] = 'S'; 
            }else{ 
                $ArrayImg['conteudo_img'] = null;
                $ArrayImg['sn_visualiza'] = 'N';
            }

            $ArrayImg['CaminhoImg'] =$CaminhoImg; 
            $ArrayImg['Caminho'] =$CaminhoImg; 
            if (str_contains($mime_type, 'image')) {
                $ArrayImg['tipo'] = 'img';
            } 
            if (str_contains($mime_type, 'pdf')) {
                $ArrayImg['tipo'] = 'pdf';
            } 

            $ArrayImg['usuario'] = $imgs->usuario?->nm_usuario;
            $ArrayImg['data'] = $imgs->data;
            $ArrayImg['cd_img_formulario'] = $imgs->cd_img_formulario;
            $dados['array_img'][] = $ArrayImg;
        }
 
    }
    
    return $dados['array_img'];
    
}
 
function helperArray_img($item)
{
    //$ArrayImg['conteudo_img'] = Storage::disk('s3')->temporaryUrl($imgs->caminho,now()->addMinutes(15));
    
    $dados['tab_img'] = Oft_formularios_imagens::where('cd_agendamento_item', $item)
        ->selectRaw("oft_formularios_imagens.*,date_format(created_at,'%d/%m/%Y') data")
        ->with('usuario')->orderBy('created_at', 'desc')->get();

    $dados['array_img']=null;
    foreach ($dados['tab_img'] as $imgs) {
        $CaminhoImg = null; 
        clearstatcache(); // Limpamos o cache de arquivos do PHP

        $CaminhoPath=(env('URL_IMG_EXAMES', '/var/img_oftalmo'));
        $CaminhoImg = $CaminhoPath . "/" . $imgs->caminho_img; 
        if (is_file($CaminhoImg)) { 

            $mime_type = mime_content_type($CaminhoImg);   
            $ArrayImg['file_size'] = filesize($CaminhoImg); 
            $data = file_get_contents($CaminhoImg); 
            $ArrayImg['tipo'] = null;
            $ArrayImg['codigo'] = $imgs->cd_img_formulario;
            $ArrayImg['olho'] =  ($imgs->olho) ? $imgs->olho : ' -- '; 
            $ArrayImg['descricao'] =  ($imgs->descricao) ? $imgs->descricao : ' ... '; 
            $ArrayImg['sn_storage'] =  ($imgs->sn_storage) ? $imgs->sn_storage : ' ?? '; 
            $ArrayImg['mime_type'] = $mime_type; 
            
            if($ArrayImg['file_size']<=8388608){  
                $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
                $ArrayImg['sn_visualiza'] = 'S'; 
            }else{ 
                $ArrayImg['conteudo_img'] = null;
                $ArrayImg['sn_visualiza'] = 'N';
            } 

            $ArrayImg['CaminhoImg'] =$CaminhoImg; 
            $ArrayImg['Caminho'] =$CaminhoImg; 
            if (str_contains($mime_type, 'image')) {
                $ArrayImg['tipo'] = 'img';
            } 
            if (str_contains($mime_type, 'pdf')) {
                $ArrayImg['tipo'] = 'pdf';
            } 

            $ArrayImg['usuario'] = $imgs->usuario?->nm_usuario;
            $ArrayImg['data'] = $imgs->data;
            $ArrayImg['cd_img_formulario'] = $imgs->cd_img_formulario;
            $dados['array_img'][] = $ArrayImg;
         
        }

    }  

    return $dados['array_img'];
}

function helperArray_img_s3($item)
{
    $dados['tab_img'] = Oft_formularios_imagens::where('cd_agendamento_item', $item)
        ->selectRaw("oft_formularios_imagens.*,date_format(created_at,'%d/%m/%Y') data")
        ->with('usuario')->orderBy('created_at', 'desc')->get();
 
    $dados['array_img']=null;
    foreach ($dados['tab_img'] as $imgs) {
        $CaminhoImg = null;
        clearstatcache(); // Limpamos o cache de arquivos do PHP
        $CaminhoImg = $imgs->caminho_img;
        if (Storage::disk('s3')->exists($CaminhoImg)) { 

            $mime_type = Storage::disk('s3')->mimeType($CaminhoImg); 
            $ArrayImg['file_size'] = Storage::disk('s3')->size($CaminhoImg); 
            $data = Storage::disk('s3')->get($CaminhoImg);

            $ArrayImg['tipo'] = null;
            $ArrayImg['codigo'] = $imgs->cd_img_formulario;
            $ArrayImg['olho'] =  ($imgs->olho) ? $imgs->olho : ' -- '; 
            $ArrayImg['descricao'] =  ($imgs->descricao) ? $imgs->descricao : ' ... '; 
            $ArrayImg['sn_storage'] =  ($imgs->sn_storage) ? $imgs->sn_storage : ' ?? '; 
            $ArrayImg['mime_type'] = $mime_type; 

            if($ArrayImg['file_size']<=8388608){     
                $ArrayImg['conteudo_img'] =  'data:' . $mime_type . ';base64,' . base64_encode($data);
                $ArrayImg['sn_visualiza'] = 'S'; 
            }else{ 
                $ArrayImg['conteudo_img'] = null;
                $ArrayImg['sn_visualiza'] = 'N';
            }

            $ArrayImg['CaminhoImg'] =$CaminhoImg; 
            $ArrayImg['Caminho'] =$CaminhoImg; 
            if (str_contains($mime_type, 'image')) {
                $ArrayImg['tipo'] = 'img';
            } 
            if (str_contains($mime_type, 'pdf')) {
                $ArrayImg['tipo'] = 'pdf';
            } 

            $ArrayImg['usuario'] = $imgs->usuario?->nm_usuario;
            $ArrayImg['data'] = $imgs->data;
            $ArrayImg['cd_img_formulario'] = $imgs->cd_img_formulario;
            $dados['array_img'][] = $ArrayImg;

        }

    }
    
    return $dados['array_img'];

}

function helperGerarSenha()
{
    $caracteres_q_farao_parte = 'abcdefghijklmnopqrstuvwxyz0123456789';
    return substr( str_shuffle($caracteres_q_farao_parte), 0, 8 );
}

function helperDiaSemana($dia){

    $diasdasemana = array (1 => "Segunda",2 => "Terça",3 => "Quarta",4 => "Quinta",5 => "Sexta",6 => "Sábado",0 => "Domingo");
    return (isset($diasdasemana[$dia])) ? $diasdasemana[$dia] : 'Erro';

}