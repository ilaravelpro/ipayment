<?php

namespace iLaravel\iPayment\Vendor\Payment;


class ParsianMethod extends TestMethod
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
            'LoginAccount' => $this->mid,
            'Amount' => (int)($amount * 10),
            'OrderId' => $order_id,
            'AdditionalData' => '',
            'CallBackUrl' => $callback,
        ];
        $soap = new \SoapClient('https://pec.shaparak.ir/NewIPGServices/Sale/SaleService.asmx?wsdl', [
            'encoding' => 'UTF-8',
        ]);
        $result = $soap->SalePaymentRequest(["requestData" => $input]);
        $status = isset($result->SalePaymentRequestResult) && $result->SalePaymentRequestResult->Status == 0 && $result->SalePaymentRequestResult->Token > 0;
        return [
            'status' => $status,
            'referral_id' => $result->SalePaymentRequestResult->Token,
            'message' => $status ? _t("Token created successfully.") : _t("Token created failed."),
            'code' => 0,
            'input' => $input,
            'output' => (array)$result->SalePaymentRequestResult,
        ];
    }

    public function redirect($transaction)
    {
        return redirect('https://pec.shaparak.ir/NewIPG/?' . http_build_query(['token' => $transaction->referral_id]));
    }

    public function verify($transaction)
    {
        $input = [
            'LoginAccount' => $this->mid,
            'Token' => request('Token'),
        ];
        $soap = new \SoapClient('https://pec.shaparak.ir/NewIPGServices/Confirm/ConfirmService.asmx?wsdl', [
            'encoding' => 'UTF-8',
        ]);
        $result = $soap->ConfirmPayment(["requestData" => $input]);
        $status = isset($result->ConfirmPaymentResult) && $result->ConfirmPaymentResult->Status == 0 && $result->ConfirmPaymentResult->RRN > 0;
        $refnum = $result->ConfirmPaymentResult->RRN;
        return [
            'status' => $status,
            'state' => $status ? 'successful' : 'unsuccessful',
            'reference_id' => $refnum,
            'transaction_id' => $refnum,
            'message' => $status ? 'Payment was successful.' : 'Payment was unsuccessful.',
            'code' => $status ? 0 : -1,
            'input' => request()->all(),
            'output' => (array)$result->ConfirmPaymentResult,
        ];
    }
}
