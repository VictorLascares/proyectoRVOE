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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('tipoUsuario',['planeacion','direccion','administrador'])->default('direccion');
            $table->tinyInteger('activo')->default('0');
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tipoUsuario');
            $table->dropColumn('activo');
            $table->dropColumn('telefono');
        });
    }
};
