<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rider_details', function (Blueprint $table) {
            $table->id();
            $table->integer('riderId');
            $table->string('licenseNumber');
            $table->string('plateNumber');
            $table->integer('starRating')->default('0');
            $table->integer('rateCount')->default('0');
            $table->foreign('riderId')->references('id')->on('users');
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
        Schema::dropIfExists('rider_details');
    }
}
