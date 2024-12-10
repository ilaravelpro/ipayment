<?php

namespace iLaravel\iPayment\Vendor\Payment;


class BehpardakhtMethod extends TestMethod
{

    public $model;
    public $configs;

    public $terminal,$username,$password;
    public $_service = null;

    public function __construct($model)
    {
        $this->model = $model;
        $this->terminal = @$this->model->authenticate['terminal'];
        $this->username = @$this->model->authenticate['username'];
        $this->password = @$this->model->authenticate['password'];
        $this->_service = new \iAmirNet\BehPardakht\BehPardakht($this->terminal, $this->username, $this->password);
    }

    public static function fast($model)
    {
        return (new static($model));
    }

    public function request($transaction, $order_id, $amount, $callback, $currency = "IRT", $description = null, $mobile = null, $email = null)
    {
        $input = [
            'order_id' => $order_id,
            'amount' => $amount,
            'callbackURL' => $callback,
            'currency' => $currency,
        ];
        $result = $this->_service->bpPayRequest(...$input);
        return [
            'status' => $result['status'],
            'referral_id' => $result['status'] ? $result['RefId'] : -1,
            'message' => $result['status'] ? _t("Token created successfully.") : _t("Token created failed."),
            'code' => 0,
            'input' => $input,
            'output' => (array)$result,
        ];
    }

    public function redirect($transaction)
    {
        return redirect_post('https://bpm.shaparak.ir/pgwchannel/startpay.mellat', ['RefId' => $transaction->referral_id]);
    }

    public function verify($transaction)
    {
        $resCode = request('ResCode');
        $input = [
            'orderId' => $transaction->order_id,
            'saleOrderId' => request('SaleOrderId'),
            'referenceId' => request('SaleReferenceId'),
        ];
        $result = intval($resCode) == 0 ? $this->_service->bpVerifyRequest(...$input) : ['status' => false, 'code' => $resCode, 'message' => $this->_service->getError($resCode)];
        if($result['status']) {
            $input['final_amount'] = request('FinalAmount');
            $input['card_pan'] = request('CardHolderPan');
            $input['card_info'] = request('CardHolderInfo');
        }
        return [
            'status' => $result['status'],
            'state' => $result['status'] ? 'successful' : 'unsuccessful',
            'reference_id' => $input['referenceId'],
            'transaction_id' => $input['saleOrderId'],
            'card_number' => request('CardHolderPan'),
            'card_hash' => request('CardHolderInfo'),
            'message' => $result['message'],
            'code' => intval($resCode),
            'input' => $input,
            'output' => (array)$result,
        ];
    }
}
