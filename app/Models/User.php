<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthUser;

class User extends AuthUser
{
    //ocultar as coluna que não precisa ser exportada
    protected $hidden = [
        'password',
        'token',
    ];


}
