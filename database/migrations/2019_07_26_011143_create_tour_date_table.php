<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTourDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_date', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tour_id');	
            $table->date('date');	
            $table->tinyInteger('status')->default(0)->comment = '0: Enabled, 1: Disabled';	
        });

        Schema::table('tour_date', function($table) {
            $table->foreign('tour_id')->references('id')->on('tour');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tour_date');
    }
}
