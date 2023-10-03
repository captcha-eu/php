<?php

namespace CaptchaEU\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

class CaptchaEU extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'captcha_eu';
    }
}
