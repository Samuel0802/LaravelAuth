<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
         //Seeds que deve rodar em produção
        if(App::environment('production')){
            $this->call([
                 UsersSeeder::class,
            ]);
        }

        //Seeds que deve rodar em desenvolvimento
        if(!App::environment('production')){
            $this->call([
                 UsersSeeder::class,
            ]);
        }
    }
}
