<?php
/*
 * Author: Amirhossein Jahani | iAmir.net
 * Email: me@iamir.net
 * Mobile No: +98-9146941147
 * Last modified: 2021/02/05 Fri 06:39 AM IRST
 * Copyright (c) 2020-2022. Powered by iAmir.net
 */

namespace iLaravel\iPayment\Providers;

use Illuminate\Support\Facades\Gate;
class AuthServiceProvider extends \Illuminate\Foundation\Support\Providers\AuthServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();
        Gate::resource('payments', 'iLaravel\Core\Vendor\iRole\iRolePolicy');
        Gate::resource('payment_accounts', 'iLaravel\Core\Vendor\iRole\iRolePolicy');
        Gate::resource('payment_transactions', 'iLaravel\Core\Vendor\iRole\iRolePolicy');
        Gate::resource('discounts', 'iLaravel\Core\Vendor\iRole\iRolePolicy');
    }
}
