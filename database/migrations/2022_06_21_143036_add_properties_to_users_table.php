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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('typeOfUser',['planeacion','direccion','administrador'])->default('direccion');
            $table->tinyInteger('active')->default('0');
            $table->string('phone',10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('typeOfUser');
            $table->dropColumn('active');
            $table->dropColumn('phone');
        });
    }
};
