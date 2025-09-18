<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

//Rotas para usuário não autenticado (VISITANTE)
Route::middleware(['guest'])->group(function () {

    //PAGINA DE LOGIN
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');

    //PAGINA DE REGISTRO
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store_user'])->name('store_user');

    //PAGINA DE CONFIRMAR REGISTRO EMAIL
    Route::get('/new_user_confirmation/{token}', [AuthController::class, 'new_user_confirmation'])->name('new_user_confirmation');

    //PAGINA DE RECUPERAÇÃO DE SENHA
    Route::get('/forgot_password', [AuthController::class, 'forgot_password'])->name('forgot_password');
    Route::post('/forgot_password', [AuthController::class, 'send_reset_password'])->name('send_reset_password');

    //PAGINA DE RESET DE SENHA
    Route::get('/reset_password/{token}', [AuthController::class, 'reset_password'])->name('reset_password');
    Route::post('/reset_password', [AuthController::class, 'reset_password_update'])->name('reset_password_update');
});

//Rotas para usuário autenticado (AUTENTICADO)
Route::middleware(['auth'])->group(function () {

    //PAGINA HOME
    Route::get('/', [MainController::class, 'home'])->name('home');

    //PAGINA DE PERFIL
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'edit_profile'])->name('password.store');

    //FUNÇÃO PARA LOGOUT
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
