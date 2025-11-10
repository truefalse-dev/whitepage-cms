<?php

namespace WhitePage\Services;

use WhitePage\Facades\WhitePage;
use WhitePage\Components\AbstractMethod;
use WhitePage\Contracts\ServiceInterface;
use Illuminate\Support\Str;

class BackendService implements ServiceInterface
{
    private string $sectionSlug;
    private string $methodSlug;
    private ?int $idSlug;
    private ?string $relationshipSlug;

    public function makeRequest($request)
    {
        [$this->sectionSlug, $this->methodSlug, $this->idSlug, $this->relationshipSlug] = $this->pathRouter($request);

        $section = app('sections')->get($this->sectionSlug);

        if ($section === null) {
            throw new \Exception("Section [$this->sectionSlug] not initialized in WhitePageProvider");
        }

        $method = $this->methodResolve();
        $id = $this->entityResolve();
        $relationship = $this->entityRelationship();
        $classMethodName = sprintf('%sMethod', Str::ucfirst($method));
        $handleMethodClass = sprintf('WhitePage\Backend\Methods\%s', $classMethodName);

        $requestMethod = Str::lower($request->getMethod());

        $requestObject = app()->make($handleMethodClass, compact('request','section', 'method', 'id', 'relationship'));

        if (!empty($request->input('reorder'))) {
            return $requestObject->reorder($request->input('reorder'));
        }

        if (!empty($request->input('delete'))) {
            return $requestObject->delete($request->input('delete'));
        }

        return $requestObject->{$requestMethod}();
    }

    private function methodResolve()
    {
        $allowed = AbstractMethod::METHODS_BACKEND;

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

    private function entityRelationship()
    {
        return $this->relationshipSlug ?? null;
    }

    public function pathRouter($request)
    {
        $parts = explode('/', $request->path());

//        if($request->ajax()) {
//            dd($parts);
//        }

        $section = null;
        $method = null;
        $id = null;
        $relationship = null;

        if (count($parts) === 3) {
            $section = $parts[1];
            $method = $parts[2];
        }

        if (count($parts) === 4) {
            $section = $parts[1];
            $method = $parts[2];
            $id = $parts[3];
        }

        if (count($parts) === 5) {
            $section = $parts[1];
            $method = $parts[2];
            $id = $parts[3];
            $relationship = $parts[4];
        }




        return [$section, $method, $id, $relationship];
    }

    public static function href($alias, $method, $id, $relationship = null)
    {
        $allowed = array_merge(AbstractMethod::METHODS, AbstractMethod::METHODS_PAGE, AbstractMethod::METHODS_BACKEND);

        if (isset($method) && !in_array($method, $allowed)) {
            throw new \Exception('Method in href() is not allowed');
        }

        $section = toPlural($alias);

        $hrefArray[] = env('APP_URL');
        $hrefArray[] = WhitePage::SERVICE_ROOT_PREFIX;
        $hrefArray[] = $section;
        $hrefArray[] = $method === AbstractMethod::LIST_METHOD ? null : $method;
        $hrefArray[] = $id;
        $hrefArray[] = $relationship;

        return implode('/', array_filter(
            $hrefArray,
            fn($item) => $item !== null
        ));
    }
}
