<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model {
    use SoftDeletes;

    protected $table = 'empresa';
    protected $primaryKey = 'cd_empresa';

    protected $fillable = [
        'nm_empresa',
        'razao_social',
        'regime',
        'insc_estadual',
        'insc_municipal',
        'cnes',
        'cidade',
        'bairro',
        'numero',
        'uf',
        'contato',
        'sn_item_agendamento',
        'email',
        'cep',
        'cnpj',
        'segunda',
        'terca',
        'quarta',
        'quinta',
        'sexta',
        'sabado',
        'domingo',
        'hr_inicial',
        'hr_final',
        'sn_ativo',
        'dt_cadastro',
        'cd_usuario',
        'logo',
        'type_logo',
        'mini_logo',
        'type_mini_logo',
        'logo_pesq_satisf',
        'type_logo_pesq_satisf',
        'end',
        'obriga_cpf',
        'valida_cpf',
        'email_contato',
        'msg_agendamento',
        'sn_laudo',
        'situacao_laudo',
        'msg_laudo',
        'msg_niver',
        'pesquisa_satisfacao',
        'tp_agenda',
        'dashboard_inicial',
        'tp_prontuario_eletronico',
        'tp_editor_html',
        'tempo_prontuario_dia',
        'sn_pre_exame',
        'recibo_atendimento',
        'key_whast',
        'api_whast',
        'atend_externo',
        'link_noticias', 
        'sn_agendamento',
        'situacao_agendamento',
        'sn_niver',
        'situacao_pesquisa',
        'sn_pesquisa',
        'sn_ag_confirm',
        'situacao_ag_confirm',
        'msg_ag_confirm',
        'sn_ag_cancel',
        'situacao_ag_cancel',
        'msg_ag_cancel',
        'sn_faltou',
        'situacao_faltou',
        'msg_faltou',
        'ag_editar_horario',
        'fila_whast',
        'url_whast',
        'color_footer'



    ];
}
