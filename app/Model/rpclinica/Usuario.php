<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    //use SoftDeletes;
    use Notifiable;

    protected $table = 'usuarios';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'cd_usuario';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'cd_usuario',
        'nm_usuario',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'cd_profissional',
        'nm_celular',
        'cd_perfil',
        'email',
        'admin',
        'sn_ativo', 
        'resetar_senha',
        'primeiro_acesso',
        'sn_triagem',
        'sn_anamnese',
        'sn_exame_fisico',
        'sn_conduta',
        'sn_hipotese_diag',
        'sn_alerta',
        'sn_documento',
        'sn_editor_html',
        'sn_exame',
        'sn_anexo',
        'sn_historico',
        'nm_header_doc',
        'espec_header_doc',
        'conselho_header_doc',
        'sn_logo_header_doc',
        'sn_footer_header_doc',
        'sn_header_doc',
        'sn_profissional',
        'sn_todos_agendamentos',
        'campos_prontuario',
        'cd_empresa',
        'campos_intervalo',
        'visualizar_exame',
        'laudar_exame',
        'email_contato',
        'sn_assina_header_doc',
        'sn_data_header_doc',
        'sn_historia_pregressa',
        'sn_carregar_historia_pregressa',
        'sn_titulo_header_doc'
    ];

    public function perfil() {
        return $this->hasOne(Perfil::class, 'cd_perfil', 'cd_perfil');
    }

    public function empresa() {
        return $this->hasOne(Empresa::class, 'cd_empresa', 'cd_empresa');
    }

    public function profissional() {
        return $this->hasOne(Profissional::class, 'cd_profissional', 'cd_profissional');
    }

    public function isRota(string $rota): bool {
        if ($this->admin == 'S') return true;

        return $this->perfil?->isRota($rota) ?? false;
    }
}
