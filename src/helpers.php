<?php

use WhitePage\Facades\WhitePage;
use WhitePage\Builders\NavigationBuilder;
use WhitePage\Services\BackendService;
use WhitePage\Services\CmsService;

function inputs()
{
    return json_encode((object) array_filter(request()->all(), fn ($input) => $input !== null));
}

function href($service, $alias, string|null $method = null, int|null $id = null, string|null $relationship = null)
{
    $appService = app(match ($service) {
        WhitePage::CMS_ROOT_PREFIX => CmsService::class,
        WhitePage::SERVICE_ROOT_PREFIX => BackendService::class,
    });

    return $appService::href($alias, $method, $id, $relationship);
}

function menu()
{
    return NavigationBuilder::menu();
}

function isInteger($value): bool
{
    $str = (string) $value;
    if (preg_match('/^-?\d+$/', $str)) {
        if (preg_match('/^-?0\d+/', $str)) {
            return false;
        }
        return true;
    }
    return false;
}

function isUuid($value): bool
{
    if (!is_string($value)) {
        return false;
    }
    return preg_match('/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}$/', $value) === 1;
}

function toPlural($word)
{
    if (preg_match('/(y)$/i', $word)) {
        return sprintf('%sies', substr($word, 0, -1));
    } else {
        return sprintf('%ss', $word);
    }
}

function fromPlural($word) {
    if (preg_match('/(ies)$/i', $word)) {
        return sprintf('%sy', substr($word, 0, -3));
    } elseif (preg_match('/(s)$/i', $word)) {
        return substr($word, 0, -1); // ends with 's'
    }
}

if (!function_exists('vite_package_assets')) {
    function vite_package_assets(array $paths): array
    {
        $packageBase = 'vendor/truefalse-dev/whitepage-cms/';

        return collect($paths)->map(fn($path) => $packageBase . $path)->all();
    }
}
