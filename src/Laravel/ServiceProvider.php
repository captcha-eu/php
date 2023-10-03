<?php

namespace CaptchaEU\Laravel;


use Illuminate\Support\ServiceProvider as Base;
use CaptchaEU\Service;

class ServiceProvider extends Base
{

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/captcha_eu.php' => config_path('captcha_eu.php'),
        ]);

        $this->app->validator->extendImplicit('captcha_eu', function ($attribute, $value) {
            return $this->app->captcha_eu->validate($value, request()->ip());
        });
    }


    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/captcha_eu.php', 'captcha_eu'
        );

        $this->app->singleton('captcha_eu', function () {
            return new Service(
                config('captcha_eu.publickey'),
                config('captcha_eu.privatekey'),
                config('captcha_eu.endpoint'),
            );
        });
    }
}
