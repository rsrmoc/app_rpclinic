<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Formulario extends Model {
    use SoftDeletes;

    protected $table = 'formulario';
    protected $primaryKey = 'cd_formulario';

    protected $fillable = [
        'nm_formulario',
        'conteudo',
        'exame',
        'hipotese',
        'conduta', 
        'sn_ativo',
        'tp_formulario',
        'cd_especialidade',
        'cd_profissional',
        'cd_usuario',
        'up_usuario',
        'sn_header',
        'tp_documento'
    ];

    protected $appends = ['conteudo_text','exame_text','hipotese_text','conduta_text'];

    public function getConteudoTextAttribute() { 
        return html_entity_decode(strip_tags($this->conteudo));
    }
    public function getExameTextAttribute() { 
        return strip_tags($this->exame);
    }
    public function getHipoteseTextAttribute() { 
        return strip_tags($this->hipotese);
    }
    public function getCondutaTextAttribute() { 
        return strip_tags($this->conduta);
    }

    public function especialidade() {
        return $this->hasOne(Especialidade::class, 'cd_especialidade', 'cd_especialidade');
    }
}
