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
    Schema::create('careers', function (Blueprint $table) {
      $table->id();
      $table->string('nombre')->nullable(false);
      $table->string('titulo')->nullable(false);
      $table->enum('modalidad', ['Presencial', 'Distancia', 'Hibrida'])->nullable(false);
      $table->integer('duracion')->nullable(false);
      $table->foreignId('institution_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('careers');
  }
};
