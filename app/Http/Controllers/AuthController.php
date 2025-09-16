<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    //PAGINA DE LOGIN
    public function login(): View
    {
        return view('auth.login');
    }

    //FUNÇÃO PARA AUTENTICAÇÃO DO USUÁRIO
    public function authenticate(Request $request)
    {

        //Validação do Form
        $regra = [
            'username' => 'required|string|min:3|max:50',
            'password' => 'required|min:6|max:32|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ];

        $feedback = [
            'username.required' => 'O usuário é Obrigatório',
            'username.string' => 'O usuário deve ter apenas Letras',
            'username.min' => 'O usuário deve ter no mínimo :min caracteres',
            'username.max' => 'O usuário deve ter no máximo :max caracteres',

            'password.required' => 'A senha é Obrigatória',
            'password.min' => 'A senha deve ter no mínimo :min caracteres',
            'password.max' => 'A senha deve ter no máximo :max caracteres',
            'password.regex' => 'A Senha deve conter pelo menos uma letra maiúscula, uma letra minúscula e um número',
        ];

        $request->validate($regra, $feedback);

        //Verificar se o usuário existe
        $user = User::where('username', $request['username'])
            //verificar se user é ativo
            ->where('ativo', true)
            //Caso blocked_until for NULL não esta bloqueado
            ->where(function ($query) {
                $query->whereNull('blocked_until')

                    //Seleciona usuários cujo bloqueio já expirou (um dia anterior).
                    ->orWhere('blocked_until', '<=', now());
            })
            //Caso campo for NULL não está verificado email
            ->whereNotNull('email_verified_at')
            //Caso deleted_at for NULL não foi apagado
            ->whereNull('deleted_at')
            ->first();

        //Verificar se o usuário for falso (Não existe)
        if (!$user) {
            return back()->withInput()->with('invalid_login', 'Login ou Senha incorretos');
        }

        //Verificar se a senha é valida
        if (!password_verify($request['password'], $user->password)) {
            return back()->withInput()->with('invalid_login', 'Login ou Senha incorretos');
        }

        //Atualizar o ultimo_login_at do usuário data atual
        $user->ultimo_login_at = now();
        //Se conseguir realizar o login coloca blocked_until como null
        $user->blocked_until = null;
        $user->save();

        //login propriamente dito!
        $request->session()->regenerate();
        Auth::login($user);

        //redirecionar
        return redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        //função de logout do auth
        Auth::logout();

        // Encerra completamente a sessão atual do usuário
        $request->session()->invalidate();

        //Gera um novo token CSRF para a sessão
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
