<?php

namespace WhitePage\Components;

use Illuminate\Http\Request;
use WhitePage\Contracts\SectionInterface;

class AuthMethod
{
    public const LOGIN_METHOD = 'login';
    public const LOGOUT_METHOD = 'logout';
    public const FORGET_PASSWORD_METHOD = 'forget-password';

    public const METHODS = [
        self::LOGIN_METHOD,
        self::LOGOUT_METHOD,
        self::FORGET_PASSWORD_METHOD,
    ];

    public function __construct(
        protected Request $request,
    ) {
    }
}
