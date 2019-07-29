<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePassengerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passenger', function (Blueprint $table) {
            $table->increments('id');
            $table->string('given_name', 128);	
            $table->string('sur_name', 64);	
            $table->string('email', 128)->nullable();	
            $table->string('mobile', 16)->nullable();	
            $table->string('passport', 16)->nullable();	
            $table->date('birth_date');	
            $table->tinyInteger('status')->default(0)->comment = '0: Enabled, 1: Disabled';	
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passenger');
    }
}
