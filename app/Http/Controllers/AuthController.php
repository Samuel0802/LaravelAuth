<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthProfileRequest;
use App\Http\Requests\AuthRegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\NewPasswordRequest;
use App\Mail\NewUserConfirmation;
use App\Mail\ResetPassword;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            // Caso blocked_until for NULL não esta bloqueado
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
            'token_created_at' => now(),
            'ativo' => false,
            'email_verified_at' => null,

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
        if (!$user) {
            return redirect()->route('login');
        }

        //Confirmar o registro do usuário
        $user->email_verified_at = Carbon::now();
        //Depois token vira null
        $user->token = null;
        //Limpar data da expiração do token
        $user->token_created_at = null;
        $user->ativo = true;
        $user->save();

        //autenticação automática (login) do usuário confirmado
        Auth::login($user);
        //redirecionar
        return view('auth.new_user_confirmation');
    }


    //PAGINA DE PERFIL DO USER
    public function profile(): View
    {
        return view('auth.profile');
    }

    //FUNÇÃO PARA ATUALIZAR A SENHA NO PERFIL
    public function edit_profile(AuthProfileRequest $request)
    {

        //Verificar se a nova password é valida -> para ser definida
        if (!password_verify($request->current_password, Auth::user()->password)) {

            return back()->with('password_invalid', 'A Senha Atual é inválida');
        }

        //Atualizar a nova senha na base da dados
        $user = Auth::user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        Auth::user()->password = $request->new_password;

        //Apresenta uma mensagem de sucesso
        return redirect()->route('profile')->with('success', 'Senha Atualizada com Sucesso');
    }

    //PAGINA PARA RECUPERAÇÃO DE SENHA
    public function forgot_password(): View
    {
        return view('auth.forgout_password');
    }

    //FUNÇÃO PARA RECUPERAÇÃO DE SENHA
    public function send_reset_password(Request $request)
    {
        //Validação do Formulário
        $regra = [
            'email' => 'required|email',
        ];

        $feedback = [

            'email.required' => 'O email é Obrigatório',
            'email.email' => 'O email deve ser válido',
        ];

        $message_generic = "Verifique a sua caixa de correio para prosseguir com a recuperação de senha";

        $request->validate($regra, $feedback);

        //Verificar se email existe
        $user = User::where('email', $request->email)->first();

        //caso for false vai gerar uma messagem generica
        if (!$user) {
            return back()->with([
                'server_massage' => $message_generic,
            ]);
        }

        //criar o link com token para enviar o email
        $user->token = Str::random(64);
        //criar data atual do token
        $user->token_created_at = Carbon::now();
        $token_link = route('reset_password', ['token' => $user->token]);

        //envio de email com link para resetar a senha
        $resultado = Mail::to($user->email)->send(new ResetPassword($user->username, $token_link));

        //verificar se o email foi enviado com sucesso
        if (!$resultado) {
            return back()->with([
                'server_massage' => $message_generic,

            ]);
        }

        //Guardar o token na base de dados
        $user->save();

        //success
        return back()->with([
            'server_massage' => $message_generic,
        ]);
    }

    //PAGINA PARA REDEFINIR A NOVA SENHA
    public function reset_password($token)
    {
        //VERIFICAR SE O TOKEN É VALIDO
        $user = User::where('token', $token)->first();

        if (!$user) {
            return redirect()->route('login');
        }

        return view('auth.reset_password', ['token' => $token, 'email' => $user->email]);
    }

    //FUNÇÃO PARA REDEFINIR A NOVA SENHA
    public function reset_password_update(NewPasswordRequest $request)
    {
        //verificar se o token é valido e email
        $user = User::where('token', $request->token)
            ->where('email', $request->email)->first();

        //Caso Token e Email for invalido ir para tela de login
        if (!$user) {
            return redirect()->route('login')->with('invalid_login', 'Token ou Email inválidos');
        }

        // Verificar se o token expirou (60 minutos)
        if ($user->token_created_at < now()->subMinutes(60)) {
            return redirect()->route('login')->with('invalid_login', 'Token Expirado');
        }

        //Atualizar a senha do user na base de dados
        $user->password = bcrypt($request->new_password);
        //invalidar o token
        $user->token = null;
        //Zerar data de expiração do token
        $user->token_created_at = null;
        //Salvar a nova senha
        $user->save();

        return redirect()->route('login')->with([
            'success' => true
        ]);
    }

    public function destroy_profile(Request $request)
    {
        //validação do formulário
        $regra = [
            'delete_confirmation' => 'required|in:ELIMINAR|min:8|max:8',
        ];

        $feedback = [
            'delete_confirmation.required' => 'O campo é obrigatório',
            'delete_confirmation.in' => 'É necessário digitar ELIMINAR',
            'delete_confirmation.min' => 'O campo deve conter 8 caracteres',
            'delete_confirmation.max' => 'O campo deve conter 8 caracteres',
        ];

        $request->validate($regra, $feedback);


        //remover a conta do usuário (HARD DELETE ou SOFT DELETE)

        $user = Auth::user();
        //Desativar o user
        $user->ativo = false;
        //salvar o status
        $user->save();

        //Soft deletes
        $user->delete();

        //Hard Deletes
        //  $user = Auth::user();
        //  $user->ForceDelete();

        //logout do user
        Auth::logout();

        //redirecionar para login
        return redirect()->route('login')->with(['conta_deletada' => true]);
    }
}
