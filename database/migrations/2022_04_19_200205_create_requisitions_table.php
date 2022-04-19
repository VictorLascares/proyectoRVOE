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
      $table->string('rvoe')->nullable()->unique();
      $table->enum('estado', ['activo', 'latencia', 'revocado', 'inactivo', 'pendiente', 'rechazado'])->nullable(false)->default('pendiente');
      $table->foreignId('career_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
      $table->string('formatoInstalaciones')->nullable();
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
