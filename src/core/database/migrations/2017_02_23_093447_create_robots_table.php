<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRobotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('robot_robotsph', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->integer('x');
            $table->integer('y');
            $table->char('heading');
            $table->string('commands');
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
        Schema::dropIfExists('robot_robotsph');
    }
}
