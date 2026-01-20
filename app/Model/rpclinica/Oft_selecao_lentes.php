<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 

class Oft_selecao_lentes extends Model { 

    protected $table = 'oft_selecao_lentes';
    protected $primaryKey = 'cd_selecao_lente';

    protected $fillable = [
        'cd_selecao_lente',
        'cd_agendamento',
        'cd_profissional',
        'lentes',
        'olhos',
        'grau',
        'grau_esferico',
        'grau_cillindro',
        'grau_longe',
        'grau_eixo',
        'grau_add',
        'obs',
        'created_at',
        'updated_at',
        'deleted_at',
        
    ];

    public function agendamento() {
        return $this->hasOne(Agendamento::class, 'cd_agendamento', 'cd_agendamento');
    }

    public function profissional() {
        return $this->hasOne(Profissional::class, 'cd_profissional', 'cd_profissional');
    }

}
