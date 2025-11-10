<?php

namespace WhitePage\Contracts;

use WhitePage\Builders\FormBuilder;
use WhitePage\Builders\ListBuilder;

interface SectionInterface
{
    public function form(FormBuilder $form);
    public function list(ListBuilder $list);
}
