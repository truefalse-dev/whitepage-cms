<?php

namespace WhitePage\Services;

use WhitePage\Facades\WhitePage;
use WhitePage\Components\AuthMethod;
use WhitePage\Contracts\ServiceInterface;
use Illuminate\Support\Str;

class AuthService implements ServiceInterface
{
    private string $sectionSlug;
    private string $methodSlug;
    private ?string $idSlug;

    public function makeRequest($request)
    {
        [$this->sectionSlug, $this->methodSlug] = $this->pathRouter($request);

        $method = $this->methodResolve();
        $classMethodName = sprintf('%sMethod', Str::ucfirst($method));
        $handleMethodClass = sprintf('WhitePage\Auth\Methods\%s', $classMethodName);

        $requestMethod = Str::lower($request->getMethod());

        return app()->make($handleMethodClass, compact('request'))
            ->{$requestMethod}();
    }

    private function methodResolve()
    {
        return $this->methodSlug;
    }

    public function pathRouter($request)
    {
        $parts = explode('/', $request->path());

        $section = null;
        $method = null;

        if (count($parts) === 3) {
            $section = $parts[1];
            $method = $parts[2];
        }

        return [$section, $method];
    }

    public static function href($alias, $method, $id)
    {
        $allowed = AuthMethod::METHODS;

        if (!in_array($alias, $allowed)) {
            throw new \Exception("Method '{$alias}' in href() is not allowed");
        }

        $hrefArray[] = env('APP_URL');
        $hrefArray[] = WhitePage::CMS_ROOT_PREFIX;
        $hrefArray[] = WhitePage::AUTH_ROOT_PREFIX;
        $hrefArray[] = $alias;

        return implode('/', array_filter(
            $hrefArray,
            fn($item) => $item !== null
        ));
    }
}
