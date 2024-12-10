<?php

namespace iLaravel\iPayment\Vendor\Payment;


class TestMethod
{

    public $model;
    public $configs;

    public $order = null;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public static function fast($model)
    {
        return (new static($model));
    }

    public function request($transaction, $order_id, $amount, $callback, $currency = "IRT", $description = null, $mobile = null, $email = null)
    {
        $input = [
            'amount' => $amount,
            'callback' => $callback,
            'description' => $description,
            'currency' => $currency,
            'mobile' => $mobile,
            'email' => $email,
        ];
        $output = [
            'token' => md5( $transaction->serial . $order_id . $amount . $currency),
        ];
        return [
            'status' => true,
            'referral_id' => $output['token'],
            'message' => _t("Token created successfully."),
            'code' => 0,
            'input' => $input,
            'output' => $output,
        ];
    }

    public function redirect($transaction)
    {
        return redirect(route('payment.providers.test.show', ['payment' => $transaction->serial]));
    }

    public function verify($transaction)
    {
        $request = request()->all();
        $status = _get_value($request, 'status', 2) == 1;
        return [
            'status' => $status,
            'state' => $status ? 'successful' : 'unsuccessful',
            'transaction_id' => request('transaction_id'),
            'message' => $status ? 'Payment was successful.' : 'Payment was unsuccessful.',
            'code' => $status ? 0 : -1,
            'input' => $request,
            'output' => [],
        ];
    }
}
