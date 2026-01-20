<?php


namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Procedimento extends Model
{ 
    use Notifiable;

    protected $table = 'procedimento';
    protected $primaryKey = 'cd_proc';

    protected $fillable = [
        'nm_proc',
        'cod_proc',
        'sn_ativo',
        'cd_tuss',
        'sn_pacote',
        'cd_grupo',
        'unidade',
        'cd_usuario',
        'up_usuario'
    ];

    public function tuss() {
        return $this->hasOne(Tuss::class, 'cd_tuss', 'cd_tuss');
    }
    public function grupo() {
        return $this->hasOne(GrupoProcedimento::class, 'cd_grupo', 'cd_grupo');
    }

    public function valor() {
        return $this->hasOne(ProcedimentoConvenio::class, 'cd_procedimento', 'cod_proc');
    }

    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario');
    }
}
