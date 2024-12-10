<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/24/21, 9:08 AM
 * Copyright (c) 2021. Powered by iamir.net
 */
if (ipayment('routes.web.payments.callback.status'))
    Route::any('callbacks/payment/{payment}', 'PaymentController@callback')->name('callbacks.payment');
if (ipayment('routes.web.payments.redirects.status'))
    Route::any('redirects/payment/{payment}', 'PaymentController@redirects')->name('redirects.payment');

Route::namespace('Providers\Payment')->prefix('providers/payment')->group(function () {
    Route::get('test/{payment}', 'TestProviderController@show')->name('payment.providers.test.show');
    Route::post('test/{payment}', 'TestProviderController@back')->name('payment.providers.test.back');
});
