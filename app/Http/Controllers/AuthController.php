<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Mail\NewUserConfirmation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthController extends Controller
{
    //PAGINA DE LOGIN
    public function login(): View
    {
        return view('auth.login');
    }

    //FUNÇÃO PARA AUTENTICAÇÃO DO USUÁRIO
    public function authenticate(LoginRequest $request)
    {
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

    //FUNÇÃO PARA LOGOUT DO USER
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

    //PAGINA DE REGISTRO
    public function register(): View
    {
        return view('auth.register');
    }

    //FUNÇÃO PARA CADASTRO DO USUÁRIO
    public function store_user(AuthRegisterRequest $request, User $user)
    {

        //Criar um novo usuário definindo um token de verificação de email
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'data_nascimento' => $request->data_nascimento,
            'genero' => $request->genero,
            'password' => bcrypt($request->password),
            'token' => Str::random(64),
        ]);

        //Gerar Link
        $confirmation_link = route('new_user_confirmation', ['token' => $user->token]);

        //Enviar Email
        $resultado = Mail::to($user->email)->send(new NewUserConfirmation($user->username, $confirmation_link));

        //Verificar se o email foi enviado com sucesso
        if (!$resultado) {
            return back()->withInput()->with('register_invalid', 'Não foi possível enviar o email de confirmação');
        }

        //Criar o usuario na base de dados

        return view('auth.email_sent', [
            'username' => $user->username,
            'email' => $user->email,
            'confirmation_link' => $confirmation_link,
        ]);
    }


    //FUNÇÃO PARA CONFIRMAR O REGISTRO DO USUÁRIO VIA EMAIL
    public function new_user_confirmation($token)
    {
        //Verificar se o token é valido
        $user = User::where('token', $token)->first();

        //Se não foi encontrado usuário
        if(!$user){
           return redirect()->route('login');
        }

        //Confirmar o registro do usuário
        $user->email_verified_at = Carbon::now();
        //Depois token vira null
        $user->token = null;
        $user->ativo = true;
        $user->save();

        //autenticação automática (login) do usuário confirmado
        Auth::login($user);
        //redirecionar
       return view('auth.new_user_confirmation');

    }
}
