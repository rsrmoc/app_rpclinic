<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class AgendamentoDocumentos extends Model
{
    protected $table = 'agendamento_documentos';
    protected $primaryKey = 'cd_documento';

    protected $fillable = [
        'cd_documento',
        'nm_formulario',
        'titulo',
        'conteudo',
        'cd_agendamento',
        'cd_formulario',
        'created_at',
        'updated_at',
        'cd_usuario',
        'cd_pac',
        'cd_prof',
        'form_assinado',
        'form_conteudo',
        'dt_assinado_digital_form',
        'user_assinado_digital_form',
        'hash_assinado_digital_form',
    ];

    protected $appends = ['conteudo_text'];
    
    public function getConteudoTextAttribute() { 
        return html_entity_decode(strip_tags($this->conteudo));
    }

    public function formulario() {
        return $this->hasOne(Formulario::class, 'cd_formulario', 'cd_formulario');
    }

    public function profissional() {
        return $this->hasOne(Profissional::class, 'cd_profissional', 'cd_prof');
    }

    public function agendamento() {
        return $this->hasOne(Agendamento::class, 'cd_agendamento', 'cd_agendamento')
        ->selectRaw("agendamento.*, date_format(data_horario,'%d/%m/%Y') data");
    }

    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario');
    }

}
