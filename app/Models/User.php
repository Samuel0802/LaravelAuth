<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //ocultar as coluna que não precisa ser exportada
    protected $hidden = [
        'password',
        'token',
    ];
}
