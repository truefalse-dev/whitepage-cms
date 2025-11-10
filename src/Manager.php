<?php

namespace WhitePage;

use WhitePage\Components\AbstractMethod;
use WhitePage\Components\AbstractSection;
use WhitePage\Contracts\ServiceInterface;
use Exception;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Manager
{
    public function app()
    {
        return $this;
    }

    public function request(ServiceInterface $service, $request)
    {
        return $service->makeRequest($request);
    }
}
