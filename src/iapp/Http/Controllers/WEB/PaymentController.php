<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/4/21, 11:36 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iPayment\iApp\Http\Controllers\WEB;

use iLaravel\Core\iApp\Http\Controllers\WEB\Controller;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use iLaravel\iPayment\Vendor\PaymentService;

class PaymentController extends Controller
{
    public $endpoint = \iLaravel\iPayment\iApp\Http\Controllers\API\v1\PaymentController::class;

    public function callback(Request $request, $payment, $redirect = 0)
    {
        $transactionModel = imodal('PaymentTransaction');
        $transaction = $transactionModel::findBySerial($payment) ?: $transactionModel::findByAny($payment);
        if (!$transaction || $transaction->payed_at)
            abort("403", "This payment has already been registered.");
        $result = PaymentService::verify($transaction);
        if ($result['redirect_method'] === "post") {
            return redirect_post($result['redirect_uri'],isset($result['redirect_data']) ? $result['redirect_data'] : [], true);
        }
        return redirect($result['redirect_uri'] . (isset($result['redirect_data']) ? ('?' . http_build_query($result['redirect_data'])) : ''));
    }

    public function redirects(Request $request, $payment)
    {
        $transactionModel = imodal('PaymentTransaction');
        $transaction = $transactionModel::findBySerial($payment) ?: $transactionModel::findByAny($payment);
        if (!$transaction || $transaction->payed_at)
            abort("403", "This payment has already been registered.");
        return PaymentService::redirect($transaction);
    }
}
