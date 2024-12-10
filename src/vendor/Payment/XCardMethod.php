<?php

namespace iLaravel\iPayment\Vendor\Payment;

class XCardMethod extends TestMethod
{
    public $api_key, $base_url;

    public function __construct($model)
    {
        $this->model = $model;
        $this->api_key = @$this->model->authenticate['api_key'];
        $this->base_url = @$this->model->authenticate['url'];
    }
    public static function fast($model)
    {
        return (new static($model));
    }

    public function request($transaction, $order_id, $amount, $callback, $currency = "IRT", $description = null, $mobile = null, $email = null)
    {
        if ($currency == "IRR")
            $amount = round($amount / 10);
        $input = [
            "amount" => $amount,
            "return_url" => $callback,
        ];
        $result = $this->service('api/v1/invoice/start', $input);
        $is_paying = isset($result['status']) && $result['status'] == 100;
        return [
            'status' => $is_paying,
            'referral_id' => $is_paying ? $result['data']["number"] : -1,
            'message' => $result['status'] ? _t("Token created successfully.") : _t("Token created failed."),
            'code' => $is_paying ? 0 : -1,
            'input' => $input,
            'output' => (array)$result,
        ];
    }

    public function redirect($transaction)
    {
        return redirect($this->base_url . 'invoice/' . $transaction->send_response['data']["number"]);
    }

    public function verify($transaction)
    {
        $result = $this->service('api/v1/invoice/check/' . $transaction->referral_id);
        $is_payed = isset($result['status']) && $result['status'] == 100;
        return [
            'status' => $is_payed,
            'state' => $is_payed ? 'successful' : 'unsuccessful',
            'reference_id' => $is_payed ? array_column($result['data']['transactions'], 'reference') : -1,
            'transaction_id' => $is_payed ? array_column($result['data']['transactions'], 'tracking":') : -1,
            'card_number' => $is_payed ? array_column($result['data']['transactions'], 'card":') : -1,
            'card_hash' => '',
            'message' => $is_payed ? 'Payment was successful.' : 'Payment was unsuccessful.',
            'code' => $is_payed ? 0 : -1,
            'input' => ['refId' => $transaction->referral_id],
            'output' => (array)$result,
        ];
    }


    public function service($url, $data = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->base_url . $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, count($data) ? json_encode($data) : '');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST , 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (count($data)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($data))
            );
        }
        $res = curl_exec($ch);
        curl_close($ch);
        return json_decode($res, true);
    }
}
