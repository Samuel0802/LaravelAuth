<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthUser;

class User extends AuthUser
{
    //ocultar as coluna que não precisa ser exportada (dd)
    // protected $hidden = [
    //     'password',
    //     'token',
    // ];

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'username',
        'genero',
        'data_nascimento',
        'ativo',
        'password',
        'token',

    ];

}
