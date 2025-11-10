<?php

namespace WhitePage\Components;

use WhitePage\Builders\FormBuilder;
use Illuminate\Support\Str;

abstract class AbstractForm extends AbstractMethod
{

    protected function buildForm($model = null): FormBuilder
    {
        return $this->section->initForm($this->request, $model)->build();
    }

    public function getTitle()
    {
        return sprintf('%s %s', Str::ucfirst($this->section->getName()), $this->method);
    }
}
