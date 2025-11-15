<?php

namespace WhitePage\Http\Controllers;

use Illuminate\Http\Request;
use WhitePage\Facades\WhitePage;
use WhitePage\Services\AuthService;
use WhitePage\Services\BackendService;
use WhitePage\Services\CmsService;

class AuthController
{
    public function login(Request $request)
    {
        return WhitePage::app()
            ->request(app(AuthService::class), $request);
    }
}
