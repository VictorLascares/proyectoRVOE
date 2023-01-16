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
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('owner', 60)->nullable(false);
            $table->string('legalRep', 60)->nullable(false);
            $table->string('email')->unique();
            $table->string('address')->nullable(false);
            $table->string('logo')->nullable(false);
            $table->string('logo_public_id')->nullable(false);
            $table->foreignId('municipalitie_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('institutions');
    }
};
