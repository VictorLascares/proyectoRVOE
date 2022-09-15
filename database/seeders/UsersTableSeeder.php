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
            ['name'=>'Axel Giovanni Coello Martínez','email'=>'axel.admin@gmail.com','tipoUsuario'=>'administrador','password'=>Hash::make('1234')],
            ['name'=>'Axel Giovanni Coello Martínez','email'=>'axel.planeacion@gmail.com','tipoUsuario'=>'planeacion','password'=>Hash::make('1234')],
            ['name'=>'Axel Giovanni Coello Martínez','email'=>'axel.direccion@gmail.com','tipoUsuario'=>'direccion','password'=>Hash::make('1234')],
            ['name'=>'Victor Manuel Lascares Gallardo','email'=>'victor.admin@gmail.com','tipoUsuario'=>'administrador','password'=>Hash::make('1234')],
            ['name'=>'Victor Manuel Lascares Gallardo','email'=>'victor.planeacion@gmail.com','tipoUsuario'=>'planeacion','password'=>Hash::make('1234')],
            ['name'=>'Victor Manuel Lascares Gallardo','email'=>'victor.direccion@gmail.com','tipoUsuario'=>'direccion','password'=>Hash::make('1234')]
        ]);
    }
}
