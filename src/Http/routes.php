<?php

use WhitePage\Facades\WhitePage;
use WhitePage\Http\Controllers\WhitePageController;
use WhitePage\Http\Middleware\DynamicRouting;
use Illuminate\Support\Facades\Route;

Route::match(
    ['post', 'get'],
    sprintf('/%s/permissions', WhitePage::CMS_ROOT_PREFIX),
    [WhitePageController::class, 'permissions']
);

Route::any(
    '/{service}/{any?}',
    [WhitePageController::class, 'sections']
)
    ->whereIn('service', [WhitePage::CMS_ROOT_PREFIX, WhitePage::SERVICE_ROOT_PREFIX])
    ->where('any', '.*')
    ->middleware(DynamicRouting::class);

Route::any('/index', [WhitePageController::class, 'index']);
