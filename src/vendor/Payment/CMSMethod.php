<?php

namespace iLaravel\iPayment\Vendor\Payment;

class CMSMethod extends TestMethod
{
    public $api_key, $base_url;

    public function __construct($model)
    {
        $this->model = $model;
        $this->api_key = @$this->model->authenticate['key'];
        $this->base_url = @$this->model->authenticate['url'];
    }
    public static function fast($model)
    {
        return (new static($model));
    }

    public function request($transaction, $order_id, $amount, $callback, $currency = "IRT", $description = null, $mobile = null, $email = null)
    {
        if ($currency == "IRT")
            $amount = round($amount * 10);
        $input = [
            "service" => "ipay",
            "action" => "pay",
            "amount" => $amount,
            "callback" => urlencode($callback),
            "description" => "#{$order_id}",
            "order_id" => $order_id,
        ];
        $result = $this->service($input);
        $is_paying = $result['status'] == "paying";
        return [
            'status' => $is_paying,
            'referral_id' => $is_paying ? $result['tran_id'] : -1,
            'message' => $result['status'] ? _t("Token created successfully.") : _t("Token created failed."),
            'code' => $is_paying ? 0 : -1,
            'input' => $input,
            'output' => (array)$result,
        ];
    }

    public function redirect($transaction)
    {
        return redirect($transaction->send_response['redirect_url']);
    }

    public function verify($transaction)
    {
        $input = [
            "service" => "ipay",
            "action" => "verify",
            "iinvoiceid" => request('ipay_id'),
        ];
        $result = $this->service($input);
        $is_payed = $result['status'] == "payed";
        if($is_payed) {
            $input['card_info'] = $result['card_hash'];
            $input['card_pan'] = $result['card'];
        }
        return [
            'status' => $is_payed,
            'state' => $is_payed ? 'successful' : 'unsuccessful',
            'reference_id' => @$input['referenceId'],
            'transaction_id' => @$input['saleOrderId'],
            'card_number' => @$result['card'],
            'card_hash' => @$result['card_hash'],
            'message' => $is_payed ? 'Payment was successful.' : 'Payment was unsuccessful.',
            'code' => $is_payed ? 0 : -1,
            'input' => $input,
            'output' => (array)$result,
        ];
    }

    public function service($data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->base_url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }
}
