<?php

namespace WhitePage\Components\Fields\Relations;

use WhitePage\Contracts\RetationshipInterface;

class BelongsTo implements RetationshipInterface
{
    public function __construct(
        public $label = 'name',
        public $id = null
    ) {
    }
}
