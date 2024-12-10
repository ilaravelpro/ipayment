<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/4/21, 11:36 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iPayment\iApp\Http\Controllers\WEB\Providers\Payment;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;


class TestProviderController extends \iLaravel\Core\iApp\Http\Controllers\WEB\Controller
{
    public $endpoint = null;
    public function __construct(Request $request)
    {
        if (!$request->route()) return;
        parent::__construct($request);
        if (!$this->endpoint){
            $endpoint = iapicontroller(class_basename($this));
            if (class_exists($endpoint)) $this->endpoint = $endpoint;
        }
    }
    public function show(Request $request, $payment) {
        $model = imodal('PaymentTransaction');
        $payment = $model::findBySerial($payment);
        if ($payment->payed_at)
            abort("403", "This payment has already been registered.");
        return view('plugins.ipayment.payments.test', ['log' => $payment]);
    }

    public function back(Request $request, $payment) {
        $model = imodal('PaymentTransaction');
        $payment = $model::findBySerial($payment);
        if ($payment->payed_at) {
            abort("403", "This payment has already been registered.");
        }
        return redirect_post($payment->send_request['callback'], $request->all());
    }
}
