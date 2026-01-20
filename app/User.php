<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    //use SoftDeletes;
    use Notifiable;

    protected $table = 'user';

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'cd_usuario';
 
    protected $fillable = [
        'cd_usuario',
        'nm_usuario',
        'password',
        'created_at',
        'updated_at',
        'deleted_at', 
        'sn_ativo',  
    ];

   
    
}
