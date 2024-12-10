<?php

namespace iLaravel\iPayment\Vendor;


use Carbon\Carbon;

class PaymentService
{
    public static $providers = [
        'test' => Payment\TestMethod::class,
        'saman' => Payment\SamanMethod::class,
        'parsian' => Payment\ParsianMethod::class,
        'behpardakht' => Payment\BehpardakhtMethod::class,
        'cms' => Payment\CMSMethod::class,
        'xcard' => Payment\XCardMethod::class,
    ];

    public static function provider($model) :Payment\TestMethod | bool {
        return @static::$providers[$model->provider] ? (new static::$providers[$model->provider]($model)) : false;
    }

    public static function model() {
        return imodal('PaymentTransaction');
    }

    public static function generateOrderId($id) {
        return date("YmdHis") . str_pad($id, 6, '0', STR_PAD_LEFT);
    }

    public static function send($modal, $amount, $meta = null, $gateway = null)
    {
        $mobile = $modal->creator->mobile;
        $email = $modal->creator->email;
        if (!($provider = static::provider($gateway = ($gateway?:$modal->payment)))) return ['status' => false, 'message' => 'Payment method not found.', 'code' => 404];
        $transaction = static::model()::create([
            "model" => class_basename($modal),
            "model_id" => $modal->id,
            "payment_id" => $gateway->id,
            "provider" => $gateway->provider,
            "ip" => _get_user_ip(),
            "amount" => $amount,
            "currency" => $modal->currency,
            "meta" => $meta,
        ]);
        $transaction->order_id = static::generateOrderId($transaction->id);
        $request = [
            'order_id' => $transaction->order_id,
            'amount' => $amount,
            'currency' => $modal->currency,
            'callback' => route('callbacks.payment', ['payment' => $transaction->serial,]),
            'description' => $modal->description,
            'mobile' => $mobile ? $mobile->text : null,
            'email' => $email ? $email->text : null
        ];
        $result = $provider->request($transaction, ...$request);
        $transaction->send_request = $result['input'];
        $transaction->send_response = $result['output'];
        $transaction->last_code = $result['code'];
        $transaction->last_message = $result['message'];
        if ($result['status']){
            $transaction->status = 'paying';
            $transaction->referral_id = $result['referral_id'];
        }else
            $transaction->status = 'bank_error';
        $transaction->save();
        $out = ['status' => $result['status'], 'message' => $result['message'], 'code' => $result['code']];
        if ($result['status']){
            $out['endpoint'] = route('redirects.payment', ['payment' => $transaction->serial,]);
            $out['referral_id'] = $result['referral_id'];
        }
        return $out;
    }
    public static function redirect($payment)
    {
        $provider = static::provider($payment->payment);
        if (!$provider) abort(404);
        return $provider->redirect($payment);
    }

    public static function verify($transaction)
    {
        if (!($provider = static::provider($gateway = $transaction->payment)))
            return ['status' => false, 'message' => 'Payment not found.', 'code' => 404];
        $result = $provider->verify($transaction);
        $transaction->verify_request = $result['input'];
        $transaction->verify_response = $result['output'];
        $transaction->last_code = $result['code'];
        $transaction->last_message = $result['message'];
        $response = ['status' => $result['status'], 'message' => $result['message'], 'code' => $result['code']];
        $transaction->status = $result['status'] ? 'payed' : 'failed';
        if ($result['status']) {
            $transaction->reference_id = @$result['reference_id'];
            $transaction->transaction_id = @$result['transaction_id'];
            $transaction->payed_at = Carbon::now()->format('Y-m-d H:i:s');
        }
        if ($mode_item = $transaction->model_item()) {
            try {
                if (method_exists($mode_item, 'payment_callback')) {
                    $mode_item->payment_callback($transaction, $response, $provider);
                }
            }catch (\Throwable $exception) {}
        }
        $transaction->save();
        return $response;
    }
}
