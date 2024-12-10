<?php


/**
 * Author: Amir Hossein Jahani | iAmir.net
 * Last modified: 1/24/21, 9:08 AM
 * Copyright (c) 2021. Powered by iamir.net
 */

namespace iLaravel\iPayment\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if($this->app->runningInConsole())
        {
            if (ipayment('database.migrations.include', true)) $this->loadMigrationsFrom(ipayment_path('database/migrations'));
        }
        View::addLocation(ipayment_path('resources/views'));
        $this->mergeConfigFrom(ipayment_path('config/ipayment.php'), 'ilaravel.main.ipayment');
    }

    public function register()
    {
        parent::register();
    }
}
