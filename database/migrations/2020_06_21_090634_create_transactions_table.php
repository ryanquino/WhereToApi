<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('clientId');
            $table->integer('riderId');
            $table->integer('restaurantId');
            $table->string('deliveryAddress');
            $table->double('deliveryCharge')->default('0');
            $table->integer('status')->default('0');
            $table->foreign('clientId')->references('id')->on('users');
            $table->foreign('riderId')->references('id')->on('users');
            $table->foreign('restaurantId')->references('id')->on('restaurants');
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
        Schema::dropIfExists('transactions');
    }
}
