<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Support\ModelPossuiLogs;

class Paciente extends Model
{
    use SoftDeletes;
    use ModelPossuiLogs;


    protected $table = 'paciente';
    protected $primaryKey = 'cd_paciente';

    protected $fillable = [
        'nm_paciente',
        'nome_social',
        'foto',
        'profissao',
        'foto_tipo',
        'cd_categoria',
        'cartao',
        'dt_validade',
        'cartao_sus',
        'dt_nasc',
        'sexo',
        'estado_civil',
        'rg',
        'cpf',
        'nm_mae',
        'dt_nasc_mae',
        'celular_mae', 
        'nm_pai',
        'dt_nasc_pai',
        'celular_pai', 
        'logradouro',
        'numero',
        'complemento',
        'nm_bairro',
        'cidade',
        'uf',
        'cep',
        'fone',
        'celular',
        'sn_whatsapp',
        'dt_whatsapp',
        'email',
        'sn_ativo',
        'cd_usuario',
        'up_cadastro',
        'vip',
        'nm_responsavel',
        'cpf_responsavel',
        'historico_problemas',
        'historico_exames',
        'comentario',
    ];

    protected $hidden = ['foto', 'foto_tipo'];
    protected $appends = [/*'foto_url',*/'idade_paciente','idade_resumido'];
    /*
    public function getFotoUrlAttribute() {
        if (empty($this->foto)) {
            return asset('assets/images/avatarPaciente.png');
        }

        return "data:image/".$this->foto_tipo.";base64,".base64_encode($this->foto);
    }
    */
    public function getIdadePacienteAttribute() { 
        return idadeAluno($this->dt_nasc);
    }

    public function getIdadeResumidoAttribute() { 
        return idadeResumido($this->dt_nasc);
    }
    
    public function anotacao() {
        return $this->hasOne(AgendamentoAnotacao::class, 'cd_paciente', 'cd_paciente');
    }

    public function convenio() {
        return $this->hasOne(Convenio::class, 'cd_convenio', 'cd_categoria');
    }

    public function agendamentos() {
        return $this->hasMany(Agendamento::class, 'cd_paciente', 'cd_paciente');
    }

    public function PacienteDocumento() {
        return $this->hasMany(PacienteDocumento::class, 'cd_paciente', 'cd_paciente');
    }

    
 
}
