<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/24/21, 10:11 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iPayment\iApp;

use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;

class Payment extends \iLaravel\Core\iApp\Model
{
    public static $s_prefix = 'ILP';
    public static $s_start = 810000;
    public static $s_end = 24299999;

    public $files = ['icon', 'image'];

    protected $casts = ['authenticate' => 'array'];
    public function creator()
    {
        return $this->belongsTo(imodal('User'), 'creator_id', 'id');
    }

    public function transactions() {
        return $this->hasMany(imodal('PaymentTransaction'));
    }
    public function provider($order) {
        return \iLaravel\iPayment\Vendor\PaymentService::provider($this);
    }

    public function getVendorAttribute() {
        return $this->provider ? ipreference('ipayment.providers.' . $this->provider . '.model') : null;
    }

    public function rules($request, $action, $arg1 = null, $arg2 = null) {
        $arg1 = $arg1 instanceof static ? $arg1 : (is_integer($arg1) ? static::find($arg1) : (is_string($arg1) ? static::findBySerial($arg1) : $arg1));
        $rules = [];
        $additionalRules = [
            'icon_file' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120',
            'image_file' => 'nullable|mimes:jpeg,jpg,png,gif|max:5120',
        ];
        switch ($action) {
            case 'store':
            case 'update':
                $rules = array_merge($rules, [
                    'title' => "required|string",
                    'slug' => ['nullable','string'],
                    'code' => ['required','string'],
                    'provider' => 'required|string',
                    'template' => 'nullable|string',
                    'summary' => "nullable|string",
                    'content' => "nullable|string",
                    'fee_title' => "nullable|string",
                    'fee_type' => "nullable|numeric",
                    'fee_value' => "nullable|numeric",
                    'discount_title' => "nullable|string",
                    'discount_type' => "nullable|numeric",
                    'discount_value' => "nullable|numeric",
                    'authenticate.*' => "nullable|string",
                    'currency' => "nullable|in:IRT",
                    'status' => 'nullable|in:' . join( ',', iconfig('status.shop_gateways', iconfig('status.global'))),
                ]);
                break;
            case 'additional':
                $rules = $additionalRules;
                break;
        }
        return $rules;
    }

}
