<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/24/21, 10:11 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iPayment\iApp;

use Illuminate\Support\Str;

class PaymentTransaction extends \iLaravel\Core\iApp\Model
{
    public static $s_prefix = 'ILPT';
    public static $s_start = 24300000;
    public static $s_end = 1733270554752;
    public static $find_names = ['number'];

    protected static function boot()
    {
        parent::boot();
        parent::creating(function ($event) {
            $now = now();
            $event->number = $now->format('Ymd'). '77' . str_pad(rand(pow(10, 3), pow(10, 6)), 7, '0', STR_PAD_LEFT) . ($now->getTimestamp() - $now->setHour(0)->setMinute(0)->floorSecond(1)->setSecond(0)->getTimestamp());
            $event->hash = Str::random(77);
        });
    }

    protected $casts = [
        'send_request' => 'array',
        'send_response' => 'array',
        'verify_request' => 'array',
        'verify_response' => 'array',
        'meta' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(imodal('User'), 'creator_id', 'id');
    }

    public function payment()
    {
        return $this->belongsTo(imodal('Payment'));
    }

    public function account()
    {
        return $this->belongsTo(imodal('PaymentAccount'));
    }

    public function model_item()
    {
        return imodal($this->model)::find($this->model_id);
    }

    public function rules($request, $action, $arg1 = null, $arg2 = null) {
        $arg1 = $arg1 instanceof static ? $arg1 : (is_integer($arg1) ? static::find($arg1) : (is_string($arg1) ? static::findBySerial($arg1) : $arg1));
        $rules = [];
        switch ($action) {
            case 'store':
            case 'update':
                $rules = array_merge($rules, [
                    'payment_id' => "required|exists:payments,id",
                    'account_id' => "required|exists:payment_accounts,id",
                    'model' => "required|string",
                    'model_id' => "required|numeric",
                    'order_id' => "nullable|numeric",
                    'provider' => "required|string",
                    'ip' => "required|string",
                    'amount' => 'required|numeric',
                    'currency' => "nullable|in:IRT",
                    'referral_id' => "nullable|string",
                    'reference_id' => "nullable|string",
                    'transaction_id' => "nullable|string",
                    'card_name' => "nullable|string",
                    'card_number' => "nullable|string",
                    'card_hash' => "nullable|string",
                    'hash' => "nullable|string",
                    'payed_at' => "nullable|date_format:Y-m-d H:i:s",
                    'checked_at' => "nullable|date_format:Y-m-d H:i:s",
                    'status' => 'nullable|in:' . join( ',', iconfig('status.payment_transactions', iconfig('status.global'))),
                ]);
                break;
        }
        return $rules;
    }
}
