<?php

namespace WhitePage\Facades;

use Illuminate\Support\Facades\Facade;

class WhitePage extends Facade
{
    public const CMS_ROOT_PREFIX = 'admin';
    public const SERVICE_ROOT_PREFIX = 'backend';

    public static function getFacadeAccessor()
    {
        return 'whitepage';
    }
}
