<?php

namespace WhitePage\Http\Controllers;

use Illuminate\Http\Request;
use WhitePage\Facades\WhitePage;
use WhitePage\Services\BackendService;
use WhitePage\Services\CmsService;

class CmsController
{
    public function sections(Request $request, string $service, $any = null)
    {
        $appService = app(match ($service) {
            WhitePage::CMS_ROOT_PREFIX => CmsService::class,
            WhitePage::BACKEND_ROOT_PREFIX => BackendService::class,
        });

        return WhitePage::app()
            ->request($appService, $request);
    }

    public function permissions()
    {
        return view('whitepage.modules.permissions');
    }
}
