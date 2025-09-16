<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    //PAGINA DE LOGIN
    public function login(): View
    {
        return view('auth.login');
    }

    //FUNÇÃO PARA AUTENTICAÇÃO
    public function authenticate(Request $request){

        echo 'Autenticando..';
    }

}
