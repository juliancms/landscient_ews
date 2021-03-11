<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRaingaugesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('raingauges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('demodbs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('rainfalldatas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('raingauge_id');
            $table->unsignedInteger('demodb_id');
            $table->dateTime('dateTime');
            $table->decimal('P1', 32, 15);
            $table->decimal('P2', 32, 15);
            $table->integer('quality');
            $table->timestamps();
            $table->foreign('raingauge_id')->references('id')->on('raingauges')->onDelete
            ('cascade');
            $table->foreign('demodb_id')->references('id')->on('demodbs')->onDelete
            ('cascade');
        });

        Schema::create('rainfallevents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('raingauge_id');
            $table->unsignedInteger('rainfalldata_id_start');
            $table->unsignedInteger('rainfalldata_id_end');
            $table->string('accum');
            $table->string('rainduration');
            $table->string('rainintensity');
            $table->timestamps();
            $table->foreign('raingauge_id')->references('id')->on('raingauges')->onDelete
            ('cascade');
            $table->foreign('rainfalldata_id_start')->references('id')->on('rainfalldatas')->onDelete
            ('cascade');
            $table->foreign('rainfalldata_id_end')->references('id')->on('rainfalldatas')->onDelete
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
        Schema::dropIfExists('rainfallevents');
        Schema::dropIfExists('demodbs');
        Schema::dropIfExists('raingauges');
    }
}
