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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id('noRequisicion');
            $table->enum('meta',['solicitud','ubicacion','renovacion'])->nullable(false);
            $table->string('rvoe')->nullable();
            $table->string('modalidad')->nullable();
            $table->integer('duracion')->nullable();
            $table->integer('idCarrera');          
            $table->integer('idInstitucion');
            $table->enum('estado',['activo','latencia','revocado','inactivo','pendiente'])->nullable(false)->default('pendiente');
            $table->foreign('idCarrera')->references('idCarrera')->on('careers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('idInstitucion')->references('idInstitucion')->on('institutions')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('requisitions');
    }
};
