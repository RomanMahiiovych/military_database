<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldierHierarchiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soldier_hierarchies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('soldier_id');
            $table->unsignedBigInteger('head_id');
            $table->integer('level');

            $table->foreign('soldier_id')->references('id')->on('soldiers');
            $table->foreign('head_id')->references('id')->on('soldiers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('soldier_hierarchies');
    }
}
