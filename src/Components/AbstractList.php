<?php

namespace WhitePage\Components;

use WhitePage\Builders\ListBuilder;
use Illuminate\Support\Str;

abstract class AbstractList extends AbstractMethod
{
    protected const DEFAULT_LIMIT = 10;

    protected $listBuilder;

    protected function listBuilder(): ListBuilder
    {
        return $this->section->initList($this->request)->build();
    }

    public function getTitle()
    {
        return Str::ucfirst(toPlural($this->section->getName()));
    }
}
