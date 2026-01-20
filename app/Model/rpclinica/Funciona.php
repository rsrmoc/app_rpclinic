<?php

namespace App\Model\rpclinica;

use App\Support\ModelPossuiLogs;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Funciona extends Authenticatable
{
    use Notifiable;

    protected $table = 'funciona';
    public $incrementing = false;
    protected $primaryKey = 'FUMATFUNC';


    protected $fillable = [
        'FUMATFUNC',
        'FUNOMFUNC',
        'FUSEXFUNC',
        'FUDTNASC',
        'FUESTCIVIL',
        'FUCODSITU',
        'FUTIPOSAL',
        'FUCODCARGO',
        'FUCPF',
        'FUCENTRCUS',
    ];


}
