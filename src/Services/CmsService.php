<?php

namespace WhitePage\Services;

use WhitePage\Facades\WhitePage;
use WhitePage\Components\AbstractMethod;
use WhitePage\Contracts\ServiceInterface;
use Illuminate\Support\Str;

class CmsService implements ServiceInterface
{
    private string $sectionSlug;
    private string $methodSlug;
    private ?string $idSlug;

    public function makeRequest($request)
    {
        [$this->sectionSlug, $this->methodSlug, $this->idSlug] = $this->pathRouter($request);

        $section = app('sections')->get($this->sectionSlug);

        if ($section === null) {
            throw new \Exception("Section [$this->sectionSlug] not initialized in WhitePageProvider");
        }

        app()->instance('section', $section);

        $method = $this->methodResolve();
        $id = $this->entityResolve();
        $classMethodName = sprintf('%sMethod', Str::ucfirst($method));
        $handleMethodClass = sprintf('WhitePage\Cms\Methods\%s', $classMethodName);

        $requestMethod = Str::lower($request->getMethod());

        return app()->make($handleMethodClass, compact('request','section', 'method', 'id'))
            ->{$requestMethod}();
    }

    private function methodResolve()
    {
        $allowed = array_merge(AbstractMethod::METHODS, AbstractMethod::METHODS_PAGE);

        if (!in_array($this->methodSlug, $allowed)) {
            abort(404);
        }

        return $this->methodSlug;
    }

    private function entityResolve()
    {
        $allowed = AbstractMethod::METHODS_PAGE;

        $hasId = isInteger($this->idSlug) || isUuid($this->idSlug);

        if (in_array($this->methodSlug, $allowed) && !$hasId) {
            abort(404);
        }

        return $this->idSlug ? (int) $this->idSlug : null;
    }

    public function pathRouter($request)
    {
        $parts = explode('/', $request->path());

        $section = null;
        $method = null;
        $id = null;

        if (count($parts) === 1) {
            $section = Dashboard::class;
            $method = 'index';
        }

        if (count($parts) === 2) {
            $section = $parts[1];
            $method = 'index';
        }

        if (count($parts) === 3) {
            $section = $parts[1];
            $method = $parts[2];
        }

        if (count($parts) === 4) {
            $section = $parts[1];
            $method = $parts[2];
            $id = $parts[3];
        }

        return [$section, $method, $id];
    }

    public static function href($alias, $method, $id)
    {
        $allowed = array_merge(AbstractMethod::METHODS, AbstractMethod::METHODS_PAGE, AbstractMethod::METHODS_BACKEND);

        if (isset($method) && !in_array($method, $allowed)) {
            throw new \Exception('Method in href() is not allowed');
        }

        $section = toPlural($alias);

        $hrefArray[] = env('APP_URL');
        $hrefArray[] = WhitePage::CMS_ROOT_PREFIX;
        $hrefArray[] = $section;
        $hrefArray[] = $method === AbstractMethod::LIST_METHOD ? null : $method;
        $hrefArray[] = $id;

        return implode('/', array_filter(
            $hrefArray,
            fn($item) => $item !== null
        ));
    }
}
