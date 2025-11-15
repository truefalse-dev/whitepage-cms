<?php

use WhitePage\Facades\WhitePage;
use WhitePage\Http\Controllers\CmsController;
use WhitePage\Http\Controllers\AuthController;
use WhitePage\Http\Middleware\DynamicRouting;
use Illuminate\Support\Facades\Route;

Route::match(
    ['post', 'get'],
    sprintf('/%s/permissions', WhitePage::CMS_ROOT_PREFIX),
    [CmsController::class, 'permissions']
);

Route::match(
    ['post', 'get'],
    'admin/auth/login',
    [AuthController::class, 'login']
)
    ->name('whitepage.login')
    ->middleware('web');

Route::any(
    '/{service}/{any?}',
    [CmsController::class, 'sections']
)
    ->whereIn('service', [WhitePage::CMS_ROOT_PREFIX, WhitePage::BACKEND_ROOT_PREFIX])
    ->where('any', '.*')
    ->middleware(['web', DynamicRouting::class]);
