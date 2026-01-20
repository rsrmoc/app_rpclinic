<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Database\Eloquent\Builder;

class Oft_reserva_cirurgia_hist extends Model {

    protected $table = 'oft_reserva_cirurgia_hist';
    protected $primaryKey = 'cd_reserva_cirurgia_hist';

    protected $fillable = [
        'cd_reserva_cirurgia_hist',
        'cd_reserva_cirurgia',
        'cd_profissional',
        'ds_historico',
        'cd_usuario',
    ];

    public function reserva_cirurgia() {
        return $this->hasOne(Oft_reserva_cirurgia::class, 'cd_reserva_cirurgia', 'cd_reserva_cirurgia');
    }
}
