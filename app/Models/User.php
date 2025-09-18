<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as AuthUser;

class User extends AuthUser
{
    //Ativar SoftDeletes
    use SoftDeletes;

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
        'token_created_at',
        'ativo',
        'password',
        'token',


    ];
}
