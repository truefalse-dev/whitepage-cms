<?php

namespace WhitePage\Facades;

use Illuminate\Support\Facades\Facade;

class WhitePage extends Facade
{
    public const CMS_ROOT_PREFIX = 'admin';
    public const BACKEND_ROOT_PREFIX = 'backend';
    public const AUTH_ROOT_PREFIX = 'auth';

    public static function getFacadeAccessor()
    {
        return 'whitepage';
    }
}
