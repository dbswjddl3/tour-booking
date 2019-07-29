<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tour_id');	
            $table->date('tour_date');	
            $table->tinyInteger('status')->default(0)->comment = '0: Submitted, 1: Confirmed, 2: Cancelled';	
        });

        Schema::table('booking', function($table) {
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
        Schema::dropIfExists('booking');
    }
}
