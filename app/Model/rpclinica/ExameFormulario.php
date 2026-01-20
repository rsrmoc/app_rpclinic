<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExameFormulario extends Model
{  
    
    protected $table = 'exames_formularios';
    protected $primaryKey = 'cd_exame_formulario';

    protected $fillable = [
        'cd_exame_formulario',
        'cd_exame',
        'nm_formulario',
        'conteudo', 
        'cd_usuario',
        'updated_at',
        'created_at', 
    ];

    public function exame() {
        return $this->hasOne(Exame::class, 'cd_exame', 'cd_exame');
    } 

    public function usuario() {
        return $this->hasOne(Usuario::class, 'cd_usuario', 'cd_usuario');
    } 
}
