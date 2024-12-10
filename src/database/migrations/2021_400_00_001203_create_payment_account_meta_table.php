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
        Schema::create('payment_account_meta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('payment_account_id')->nullable()->unsigned();
            $table->foreign('payment_account_id')->references('id')->on('payment_accounts')->onDelete('cascade');
            $table->string('type')->nullable();
            $table->string('key')->index();
            $table->longText('value')->nullable();
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
        Schema::dropIfExists('payment_account_meta');
    }
};
