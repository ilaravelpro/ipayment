<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/24/21, 9:08 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

return [
    'routes' => [
        'api' => [
            'status' => true,
            'payments' => ['status' => true],
            'payment_accounts' => ['status' => true],
            'payment_transactions' => ['status' => true],
        ],
        'web' => [
            'status' => true,
            'providers' => [
                'payment' => [
                    'test' => [
                        'status' => true
                    ]
                ]
            ],
            'payments' => [
                'status' => true,
                'callback' => [
                    'status' => true
                ],
                'redirects' => [
                    'status' => true
                ]
            ]
        ]
    ],
    'database' => [
        'migrations' => [
            'include' => true
        ],
    ],
    'providers' => [
        'test' => [
            'name' => 'test',
            'title' => 'Test',
            'model' => \iLaravel\iPayment\Vendor\Payment\TestMethod::class
        ],
        'cmc' => [
            'name' => 'cms',
            'title' => _t('CMS Payment'),
            'model' => \iLaravel\iPayment\Vendor\Payment\CMSMethod::class,
            'authenticate' => [
                [
                    'label' => _t('CMC Url'),
                    'name' => 'url'
                ],
                [
                    'label' => _t('Api Key'),
                    'name' => 'key'
                ]
            ],
        ],
        'xcard' => [
            'name' => 'xcard',
            'title' => _t('XCard Payment'),
            'model' => \iLaravel\iPayment\Vendor\Payment\XCardMethod::class,
            'authenticate' => [
                [
                    'label' => _t('XCard Url'),
                    'name' => 'url'
                ],
                [
                    'label' => _t('Api Key'),
                    'name' => 'key'
                ]
            ],
        ],
        'behpardakht' => [
            'name' => 'behpardakht',
            'title' => _t('Behpardakht'),
            'model' => \iLaravel\iPayment\Vendor\Payment\BehpardakhtMethod::class,
            'authenticate' => [
                [
                    'label' => _t('Terminal No'),
                    'name' => 'terminal'
                ],
                [
                    'label' => _t('Username'),
                    'name' => 'username'
                ],
                [
                    'label' => _t('Password'),
                    'name' => 'password'
                ]
            ],
        ],
        'parsian' => [
            'name' => 'parsian',
            'title' => _t('Parsian Bank'),
            'model' => \iLaravel\iPayment\Vendor\Payment\ParsianMethod::class,
            'authenticate' => [
                [
                    'label' => _t('Merchant ID'),
                    'name' => 'mid'
                ]
            ],
        ],
        'saman' => [
            'name' => 'saman',
            'title' => _t('Saman Bank'),
            'model' => \iLaravel\iPayment\Vendor\Payment\SamanMethod::class,
            'authenticate' => [
                [
                    'label' => _t('Merchant ID'),
                    'name' => 'mid'
                ]
            ],
        ],
    ]
];
?>
