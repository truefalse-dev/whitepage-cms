<?php

namespace WhitePage\Components\Fields\Relations;

use WhitePage\Contracts\RetationshipInterface;

class MorphToMany implements RetationshipInterface
{
    public function __construct(
        public $label = 'name',
        public $id = null
    ) {
    }
}
