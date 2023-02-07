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
        Schema::create('plans', function (Blueprint $table) {
            $table->id(); //identificador
            $table->integer('plan')->nullable(false); //Plan del 1 - 29 :: 1-12 46 _ 13 - 29  142
            $table->enum('status', ['cumple', 'parcialmente', 'na'])->nullable()->default('na'); //Indicadores
            $table->text('commentary')->nullable(); //Comentario
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
        Schema::dropIfExists('plans');
    }
};
