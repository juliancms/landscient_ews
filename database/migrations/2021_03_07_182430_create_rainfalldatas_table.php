<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRainfalldatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rainfalldatas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('raingauge_id');
            $table->dateTime('dateTime');
            $table->decimal('P1', 32, 15);
            $table->decimal('P2', 32, 15);
            $table->integer('quality');
            $table->timestamps();
            $table->foreign('raingauge_id')->references('id')->on('raingauges')->onDelete
            ('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rainfalldatas');
    }
}
