<?php

namespace WhitePage\Backend\Methods;

use WhitePage\Components\AbstractForm;
use WhitePage\Models\Repository;

class FormMethod extends AbstractForm
{
    public function get()
    {
        $model = $this->section->getModelClass()->find($this->id);

        $form = $this->buildForm($model);

        $fields = $form->getFields()
            ->map(fn ($field) => array_merge(
                [
                    'name' => $field->getName(),
                    'label' => $field->getLabel(),
                    'type' => $field->getType(),
                    'value' => $field->getValue(),
                ],
                $field->getType() === 'select' ? ['options' => $field->getOptions()] : []
            ))
            ->values();

        return json_encode(compact('fields'));
    }

    public function post()
    {
        $model = $this->section->getModelClass()->find($this->id);

        $form = $this->buildForm($model);

        $postData = $form->passed();

        if ($postData->count()) {
            Repository::make($model)
                ->commit($postData);
        }
    }
}
