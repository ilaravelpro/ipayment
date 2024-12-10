<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/24/21, 9:08 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('creator_id')->nullable()->unsigned();
            $table->foreign('creator_id')->references('id')->on('users');
            $table->bigInteger('payment_id')->nullable()->unsigned();
            $table->foreign('payment_id')->references('id')->on('payments');
            $table->string('title')->nullable();
            $table->string('address')->nullable();
            $table->string('address_second')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('fee_title')->nullable();
            $table->bigInteger('fee_type')->nullable();
            $table->bigInteger('fee_value')->nullable();
            $table->bigInteger('discount_title')->nullable();
            $table->bigInteger('discount_type')->nullable();
            $table->bigInteger('discount_value')->nullable();
            $table->integer('position')->default(0);
            $table->string('status')->default('draft');
            $table->longText('authenticate')->nullable();
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
        Schema::dropIfExists('payment_accounts');
    }
};

