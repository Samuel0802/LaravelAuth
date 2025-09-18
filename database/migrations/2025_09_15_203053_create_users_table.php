<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username', 50)->unique();
            $table->string('email', 100)->unique();
            $table->date('data_nascimento');
            $table->enum('genero', ['masculino', 'feminino', 'outro']);
            $table->string('password', 200);
            $table->string('token', 100)->nullable();
            $table->dateTime('token_created_at')->nullable();//expiração do token
            $table->dateTime('email_verified_at')->nullable()->default(null);
            $table->dateTime('ultimo_login_at')->nullable()->default(null); //ultimo login
            $table->boolean('ativo')->nullable()->default(null); //user ativo / ñ ativo
            $table->dateTime('blocked_until')->nullable()->default(null); //bloquear user
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
