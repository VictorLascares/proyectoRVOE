<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'nombres' => 'John Doe',
            'apellidos' => 'Coello Martínez',
            'correo' => 'john@doe.com',
            'contrasenia' => Hash::make('secretosdeto2')
        ]);
    }
}
