<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('tipoUsuario',['planeacion','direccion','administrador'])->default('direccion');
            $table->string('contrasenia',15)->nullable(false);
            $table->boolean('estado')->default(false);
            $table->string('nombres',30)->nullable(false);
            $table->string('apellidos',30)->nullable(false);
            $table->string('correo',60)->nullable();
            $table->string('telefono',10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
