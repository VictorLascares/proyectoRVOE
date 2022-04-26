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
      $table->id();
      $table->string('elemento')->nullable(false);
      $table->boolean('existente')->default(true);
      $table->string('observacion')->nullable();
      $table->foreignId('requisition_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
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
