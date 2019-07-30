<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingPassengerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_passenger', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('booking_id');	
            $table->unsignedInteger('passenger_id');	
            $table->text('special_request')->nullable();	
        });

        Schema::table('booking_passenger', function($table) {
            $table->foreign('booking_id')->references('id')->on('booking')->onDelete('cascade');
            $table->foreign('passenger_id')->references('id')->on('passenger')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_passenger');
    }
}
