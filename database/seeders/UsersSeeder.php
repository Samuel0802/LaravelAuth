<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{

    public function run(): void
    {
        //Adicionando 3 usuario no database
        for($index = 1; $index <= 3; $index++){

            User::create([
              'username' => "user$index",
              'email' => "user$index@gmail.com",
              'genero' => "masculino",
              'data_nascimento' => "1990-0{$index}-0{$index}",
              'password' => bcrypt('123456'),
              'email_verified_at' => Carbon::now(),
              'ativo' => true,
            ]);
        }

    }
}
