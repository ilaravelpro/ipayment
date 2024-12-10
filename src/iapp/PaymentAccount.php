<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/24/21, 10:11 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iPayment\iApp;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;
use iLaravel\iPayment\Vendor\PaymentService;

class PaymentAccount extends \iLaravel\Core\iApp\Model
{
    use \iLaravel\Core\iApp\Methods\Metable;
    public static $s_prefix = 'ILPA';
    public static $s_start = 810000;
    public static $s_end = 24299999;

    public $metaTable = 'payment_account_meta';

    protected $hidden = ['metas'];

    public function getTitleAttribute()
    {
        return $this->name;
    }

    public function creator()
    {
        return $this->belongsTo(imodal('User'), 'creator_id', 'id');
    }
    public function payment()
    {
        return $this->belongsTo(imodal('Payment'), 'payment_id');
    }

    public function transactions() {
        return $this->hasMany(imodal('PaymentTransaction'), 'account_id');
    }
    public function rules($request, $action, $arg1 = null, $arg2 = null) {
        $arg1 = $arg1 instanceof static ? $arg1 : (is_integer($arg1) ? static::find($arg1) : (is_string($arg1) ? static::findBySerial($arg1) : $arg1));
        $rules = [];
        switch ($action) {
            case 'store':
            case 'update':
                $rules = array_merge($rules, [
                    'payment_id' => "required|exists:payments,id",
                    'title' => "required|string",
                    'address' => "required|string",
                    'address_second' => "nullable|string",
                    'description' => "nullable|string",
                    'fee_title' => "nullable|string",
                    'fee_type' => "nullable|numeric",
                    'fee_value' => "nullable|numeric",
                    'discount_title' => "nullable|string",
                    'discount_type' => "nullable|numeric",
                    'discount_value' => "nullable|numeric",
                    'authenticate.*' => "nullable|string",
                    'currency' => "nullable|in:IRT",
                    'status' => 'nullable|in:' . join( ',', iconfig('status.payment_accounts', iconfig('status.global'))),
                ]);
                break;
        }
        return $rules;
    }

}
