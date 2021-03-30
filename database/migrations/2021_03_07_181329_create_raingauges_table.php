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

        Schema::create('studysites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('alpha');
            $table->decimal('beta');
            $table->integer('duration_initial');
            $table->integer('duration_final');
            $table->timestamps();
        });

        Schema::create('raingauges', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('studysite_id');
            $table->string('name');
            $table->timestamps();
            $table->foreign('studysite_id')->references('id')->on('studysites')->onDelete
            ('cascade');
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
            $table->decimal('intensityratio', 32, 15)->default(0);
            $table->decimal('advisorylevel', 32, 15)->default(0);
            $table->integer('advisorylevel_duration')->default(0);
            $table->integer('rainfallevent_duration')->default(0);
            $table->timestamps();
            $table->foreign('raingauge_id')->references('id')->on('raingauges')->onDelete
            ('cascade');
            $table->foreign('demodb_id')->references('id')->on('demodbs')->onDelete
            ('cascade');
        });

        Schema::create('simulations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('raingauge_id');
            $table->unsignedInteger('demodb_id');
            $table->timestamps();
            $table->foreign('raingauge_id')->references('id')->on('raingauges')->onDelete
            ('cascade');
            $table->foreign('demodb_id')->references('id')->on('demodbs')->onDelete
            ('cascade');
        });

        Schema::create('rainfallevents', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('simulation_id');
            $table->unsignedInteger('rainfalldata_id_start');
            $table->unsignedInteger('rainfalldata_id_end');
            $table->string('accum');
            $table->string('rainduration');
            $table->string('rainintensity');
            $table->timestamps();
            $table->foreign('simulation_id')->references('id')->on('simulations')->onDelete
            ('cascade');            
            $table->foreign('rainfalldata_id_start')->references('id')->on('rainfalldatas')->onDelete
            ('cascade');
            $table->foreign('rainfalldata_id_end')->references('id')->on('rainfalldatas')->onDelete
            ('cascade');
        });

        Schema::create('advisorylevels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rainfallevent_id');
            $table->decimal('intensityratio', 32, 15);
            $table->integer('duration');
            $table->timestamps();
            $table->foreign('rainfallevent_id')->references('id')->on('rainfallevents')->onDelete
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
