<?php

namespace iLaravel\iPayment\Vendor\Payment;


class SamanMethod extends TestMethod
{

    public $model;
    public $configs;

    public $mid = null;
    public $order = null;

    public function __construct($model)
    {
        $this->model = $model;
        $this->mid = @$this->model->authenticate['mid'];
    }

    public static function fast($model)
    {
        return (new static($model));
    }

    public function request($transaction, $order_id, $amount, $callback, $currency = "IRT", $description = null, $mobile = null, $email = null)
    {
        $input = [
            'order_id' => $order_id,
            'amount' => (string)($amount * 10),
            'callback' => $callback,
        ];
        $soap = new \SoapClient('https://sep.shaparak.ir/Payments/InitPayment.asmx?WSDL', [
            'encoding' => 'UTF-8',
            'cache_wsdl' => WSDL_CACHE_NONE,
            'stream_context' => stream_context_create([
                'ssl' => [
                    'ciphers' => 'DEFAULT:!DH',
                ],
            ]),]);
        $token = $soap->RequestToken($this->mid, $input['order_id'], $input['amount'], "0", "0", "0", "0", "0", "0", (string)$transaction->serial, "", "0", $input['callback']);
        return [
            'status' => true,
            'referral_id' => $token,
            'message' => _t("Token created successfully."),
            'code' => 0,
            'input' => $input,
            'output' => ['token' => $token],
        ];
    }

    public function redirect($transaction)
    {
        return redirect_post('https://sep.shaparak.ir/payment.aspx', ['Token' => urlencode($transaction->referral_id), 'RedirectURL' => $transaction->send_request['callback']]);
    }

    public function verify($transaction)
    {
        $client = new \SoapClient('https://verify.sep.ir/Payments/ReferencePayment.asmx?WSDL', [
            'encoding' => 'UTF-8',
            'cache_wsdl' => WSDL_CACHE_NONE,
            'stream_context' => stream_context_create([
                'ssl' => [
                    'ciphers' => 'DEFAULT:!DH',
                ],
            ]),
        ]);
        $refnum = request('RefNum');
        $result = $client->VerifyTransaction($refnum, $this->mid);
        $status = $result > 0;
        return [
            'status' => $status,
            'state' => $status ? 'successful' : 'unsuccessful',
            'reference_id' => $refnum,
            'transaction_id' => $refnum,
            'message' => $status ? 'Payment was successful.' : 'Payment was unsuccessful.',
            'code' => $result > 0 ? 0 : -1,
            'input' => request()->all(),
            'output' => $result,
        ];
    }
}
