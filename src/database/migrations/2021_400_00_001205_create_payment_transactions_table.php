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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('creator_id')->nullable()->unsigned();
            $table->foreign('creator_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('payment_id')->nullable()->unsigned();
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->string('number')->nullable();
            $table->string('order_id')->nullable();
            $table->string('model')->nullable();
            $table->string('model_id')->nullable();
            $table->string('provider')->nullable();
            $table->string('ip')->nullable();
            $table->bigInteger('value')->nullable();
            $table->bigInteger('valued')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->bigInteger('amounted')->nullable();
            $table->string('currency')->nullable()->default('IRT');
            $table->text('referral_id')->nullable();
            $table->text('reference_id')->nullable();
            $table->text('transaction_id')->nullable();
            $table->string('address_type')->nullable()->default('card');
            $table->string('address_owner')->nullable();
            $table->string('address_text')->nullable();
            $table->text('address_hash')->nullable();
            $table->integer('confirmations')->nullable();
            $table->longText('last_code')->nullable();
            $table->longText('last_message')->nullable();
            $table->longText('send_request')->nullable();
            $table->longText('send_response')->nullable();
            $table->longText('verify_request')->nullable();
            $table->longText('verify_response')->nullable();
            $table->longText('hash')->nullable();
            $table->longText('meta')->nullable();
            $table->string('status')->default('processing');
            $table->timestamp('payed_at')->nullable();
            $table->timestamp('checked_at')->nullable();
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
        Schema::dropIfExists('payment_transactions');
    }
};

