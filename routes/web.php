<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

//Rotas para usuário não autenticado (VISITANTE)
Route::middleware(['guest'])->group(function(){

     //PAGINA DE LOGIN
     Route::get('/login', [AuthController::class, 'login'])->name('login');
     Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');

     //PAGINA DE REGISTRO
     Route::get('/register', [AuthController::class, 'register'])->name('register');
     Route::post('/register', [AuthController::class, 'store_user'])->name('store_user');

     //PAGINA DE CONFIRMAR REGISTRO EMAIL
     Route::get('/new_user_confirmation/{token}',[AuthController::class, 'new_user_confirmation'])->name('new_user_confirmation');
});

Route::middleware(['auth'])->group(function(){
    Route::get('/', function(){
             echo 'Home';
    })->name('home');

   Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
