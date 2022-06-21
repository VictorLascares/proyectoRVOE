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
            $table->id();
            $table->enum('meta', ['solicitud', 'domicilio', 'planEstudios'])->nullable(false);
            $table->integer('numero_solicitud')->nullable();
            $table->string('rvoe')->nullable()->unique();
            $table->string('formatoInstalaciones')->nullable();
            $table->enum('estado', ['activo', 'latencia', 'revocado', 'inactivo', 'pendiente', 'rechazado'])->nullable(false)->default('pendiente');
            $table->integer('noEvaluacion')->default('1');
            $table->boolean('cata')->nullable(); 
            $table->date('fecha_vencimiento')->nullable();
            $table->date('fecha_latencia')->nullable();
            $table->foreignId('career_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
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
