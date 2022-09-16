<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('areas')->insert([
            ['nombre'=>'Arquitectura, Urbanismo y Diseño'],
            ['nombre'=>'Artes'],
            ['nombre'=>'Agronomia Veterinaria'],
            ['nombre'=>'Ciencias Biologias'],
            ['nombre'=>'Ciencias Fisico Matematicas'],
            ['nombre'=>'Ciencias Sociales'],
            ['nombre'=>'Economico Administrativas'],
            ['nombre'=>'Educacion'],
            ['nombre'=>'Humanidades'],
            ['nombre'=>'Ingenierias']    
        ]);
    }
}
