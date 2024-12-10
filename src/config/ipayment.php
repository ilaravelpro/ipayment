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
            'title' => 'سیستم تولید محتوا',
            'model' => \iLaravel\iPayment\Vendor\Payment\CMSMethod::class,
            'authenticate' => [
                [
                    'label' => 'آدرس CMS',
                    'name' => 'url'
                ],
                [
                    'label' => 'کلیدخصوصی',
                    'name' => 'key'
                ]
            ],
        ],
        'xcard' => [
            'name' => 'xcard',
            'title' => 'ایکس کارت',
            'model' => \iLaravel\iPayment\Vendor\Payment\XCardMethod::class,
            'authenticate' => [
                [
                    'label' => 'آدرس ایکس‌کارت',
                    'name' => 'url'
                ],
                [
                    'label' => 'کلیدخصوصی',
                    'name' => 'key'
                ]
            ],
        ],
        'behpardakht' => [
            'name' => 'behpardakht',
            'title' => 'به‌پرداخت ملت',
            'model' => \iLaravel\iPayment\Vendor\Payment\ParsianMethod::class,
            'authenticate' => [
                [
                    'label' => 'شماره پایانه',
                    'name' => 'terminal'
                ],
                [
                    'label' => 'نام‌کاربری',
                    'name' => 'username'
                ],
                [
                    'label' => 'رمزعبور',
                    'name' => 'password'
                ]
            ],
        ],
        'parsian' => [
            'name' => 'parsian',
            'title' => 'پارسیان',
            'model' => \iLaravel\iPayment\Vendor\Payment\ParsianMethod::class,
            'authenticate' => [
                [
                    'label' => 'مرچنت‌کد',
                    'name' => 'mid'
                ]
            ],
        ],
        'saman' => [
            'name' => 'saman',
            'title' => 'سامان',
            'model' => \iLaravel\iPayment\Vendor\Payment\SamanMethod::class,
            'authenticate' => [
                [
                    'label' => 'مرچنت‌کد',
                    'name' => 'mid'
                ]
            ],
        ],
    ]
];
?>
