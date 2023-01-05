<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaddersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ladders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('year');
            $table->integer('rank');
            $table->integer('round');
            $table->integer('userId');
            $table->string('teamTipped');
            $table->integer('roundPoints');
            $table->integer('totalPoints');
            $table->boolean('powerTip');
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
        Schema::dropIfExists('ladders');
    }
}
