<?php

namespace App\Model\rpclinica;

use Illuminate\Database\Eloquent\Model;

class ViewsRelatorios extends Model
{
    //
    protected $fillable = [
        'nome',
        'query'
    ];
}
