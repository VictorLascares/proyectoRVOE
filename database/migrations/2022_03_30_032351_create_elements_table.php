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
        Schema::create('elements', function (Blueprint $table) {
            $table->id('noFormato');
            $table->string('nombre')->nullable(false);
            $table->boolean('valido')->nullable(false);
            $table->string('observacion')->nullable();
            $table->integer('noEvaluacion')->nullable(false);
            $table->integer('ponderacion')->nullable();
            $table->integer('noRequisicion');
            $table->foreign('noRequisicion')->references('noRequisicion')->on('requisitions')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('elements');
    }
};
