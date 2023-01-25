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
        Schema::create('opinios', function (Blueprint $table) {
            $table->id(); //identificador
            $table->integer('opinion')->nullable(false); //Opinion 1 - 29
            $table->float('top')->nullable();  // Maximo
            $table->enum('status', ['suficiente', 'insuficiente', 'na'])->nullable(false)->default('NA'); //Indicadores
            $table->foreignId('requisition_id')->constrained()->onUpdate('cascade')->onDelete('cascade'); 
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
        Schema::dropIfExists('opinios');
    }
};
