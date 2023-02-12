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
            $table->enum('procedure', ['solicitud', 'domicilio', 'planEstudios'])->nullable(false);
            $table->integer('requestNumber')->nullable();
            $table->string('rvoe')->nullable()->unique();
            $table->string('facilitiesFormat')->nullable();
            $table->string('format_public_id')->nullable();
            $table->string('opinionFormat')->nullable();
            $table->string('opinion_public_id')->nullable();
            $table->string('planFormat')->nullable();
            $table->string('plan_public_id')->nullable();
            $table->enum('status', ['activo', 'latencia', 'revocado', 'inactivo', 'pendiente', 'rechazado'])->nullable(false)->default('pendiente');
            $table->integer('evaNum')->default('1');
            $table->boolean('cata')->nullable(); 
            $table->boolean('ota')->default(false); 
            $table->date('dueDate')->nullable();
            $table->date('latencyDate')->nullable();
            $table->date('requisitionDate')->nullable();
            $table->date('revokedDate')->nullable();
            $table->date('fecha_managment')->nullable();
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
