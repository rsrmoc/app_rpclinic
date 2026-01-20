<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class Tuss extends Model
{
    protected $table = 'tuss';
    protected $primaryKey = 'cd_tuss';

    protected $fillable = [
        'codigo',
        'nome',
        'sn_ans',
        'nm_ans',
        'sub_grupo',
        'grupo',
        'capitulo',
        'dut'
    ];
}
