<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSoldiersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soldiers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_entry');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->integer('salary');
            $table->string('image')->nullable();
            $table->bigInteger('rank_id');
            $table->timestamps();

            $table->foreign('rank_id')->references('id')->on('ranks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('soldiers');
    }
}
