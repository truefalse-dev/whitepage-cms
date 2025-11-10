<?php

namespace WhitePage\Cms\Methods;

use WhitePage\Facades\WhitePage;
use WhitePage\Components\AbstractForm;
use WhitePage\Models\Repository;

class StoreMethod extends AbstractForm
{
    public function get()
    {
        $title = $this->getTitle();

        $form = $this->buildForm();
        $method = $this->method;

        $view = sprintf('%s.page', $this->section->getSectionResourcesPath());
        return view($view,  compact(
            'title',
            'form',
            'method'
        ));
    }

    public function post()
    {
        $form = $this->buildForm();
        $method = $this->method;

        $postData = $form->passed();

        if ($postData->count()) {

            Repository::make($this->section->getModelClass())
                ->commit($postData);

            return [
                'redirect_url' => href(WhitePage::CMS_ROOT_PREFIX, $this->section->getName()),
            ];
        }

        return view($this->getFormPath(),  compact(
            'form',
            'method'
        ));
    }
}
