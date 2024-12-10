<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/24/21, 9:08 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

Route::namespace('v1')->prefix('v1')->middleware('auth:api')->group(function () {
    if (ipayment('routes.api.payments.status'))
        Route::apiResource('payments', 'PaymentController', ['as' => 'api']);
    if (ipayment('routes.api.payment_accounts.status'))
        Route::apiResource('payment_accounts', 'PaymentAccountController', ['as' => 'api']);
    if (ipayment('routes.api.payment_transactions.status'))
        Route::apiResource('payment_transactions', 'PaymentTransactionsController', ['as' => 'api']);
});


Route::namespace('v1')->prefix('v1')->group(function () {
    Route::get('payment/providers', 'PaymentController@providers', ['as' => 'api.payments.providers']);
});
