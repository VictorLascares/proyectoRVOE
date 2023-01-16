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
            ['name'=>'Educación'],
            ['name'=>'Artes y humanidades'],
            ['name'=>'Ciencias sociales y derecho'],
            ['name'=>'Administración y negocios'],
            ['name'=>'Ciencias naturales, matemáticas y estadística'],
            ['name'=>'Tecnologías de información y la comunicación'],
            ['name'=>'Ingeniería, manufactura y construcción'],
            ['name'=>'Agronomía y veterinaria'],
            ['name'=>'Ciencias de la salud'],
            ['name'=>'Servicios']    
        ]);
    }
}
