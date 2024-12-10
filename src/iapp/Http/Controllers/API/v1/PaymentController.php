<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 2/4/21, 11:36 PM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iPayment\iApp\Http\Controllers\API\v1;

use iLaravel\Core\iApp\Exceptions\iException;
use iLaravel\Core\iApp\Http\Controllers\API\ApiController;
use iLaravel\Core\iApp\Http\Requests\iLaravel as Request;


class PaymentController extends ApiController
{
    public $order_list = ['id', 'name', 'code', 'provider', 'wage', 'description', 'property'];

    public function callback(Request $request, $payment) {
        $transactionModel = imodal('PaymentTransaction');
        $transaction = $transactionModel::findBySerial($payment)?:$transactionModel::findByAny($payment);
        if (!$transaction || $transaction->payed_at)
            throw new iException("This payment has already been registered.");
        return ['data' => $transaction->payment->provider($transaction)->verify()];
    }

    public function providers(Request $request) {
        return [
            'data' => array_values(array_map(function ($item) {
                return [
                    'text' => $item['title'],
                    'value' => $item['name'],
                ];
            }, ipayment('providers', [])))
        ];
    }

    public function filters($request, $model, $parent = null, $operators = [])
    {
        $current = [];
        $filters = [
            [
                'name' => 'all',
                'title' => _t('all'),
                'type' => 'text',
            ],
            [
                'name' => 'name',
                'title' => _t('name'),
                'type' => 'text'
            ],
            [
                'name' => 'code',
                'title' => _t('code'),
                'type' => 'text'
            ],
            [
                'name' => 'provider',
                'title' => _t('provider'),
                'type' => 'text'
            ],
            [
                'name' => 'wage',
                'title' => _t('wage'),
                'type' => 'text'
            ],
            [
                'name' => 'description',
                'title' => _t('description'),
                'type' => 'text'
            ],
            [
                'name' => 'property',
                'title' => _t('property'),
                'type' => 'text'
            ],
        ];
        return [$filters, $current, $operators];
    }
}
