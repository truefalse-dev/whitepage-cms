<?php

namespace WhitePage\Cms\Methods;

use Facades\WhitePage;
use WhitePage\Components\AbstractForm;
use WhitePage\Models\Repository;

class UpdateMethod extends AbstractForm
{
    public function get()
    {
        $id = $this->id;
//        $model = $this->section->initModel($this->id);
//
//        if (empty($model)) {
//            return redirect()->to(href(WhitePage::CMS_ROOT_PREFIX, $this->section->getName()));
//        }

        $title = $this->getTitle();
//        $form = $this->buildForm($model);
//        $method = $this->method;
//
//        $relationships = $form->getRelationships();

        $view = sprintf('%s.page', $this->section->getSectionResourcesPath());
        return view($view,  compact(
            'id',
            'title',
//            'form',
//            'method',
//            'relationships',
        ));
    }

    public function post()
    {
        $model = $this->section->getModelClass()->find($this->id);

        $form = $this->buildForm($model);
        $method = $this->method;

        $postData = $form->passed();

        if ($postData->count()) {

            Repository::make($model)
                ->commit($postData);

            return [
                'redirect_url' => href(WhitePage::CMS_ROOT_PREFIX, $this->section->getName()),
            ];
        }

        return view($this->getFormPath(),  compact(
            'form',
            'method',
        ));
    }
}
