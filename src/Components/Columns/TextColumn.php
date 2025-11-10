<?php

namespace WhitePage\Components\Columns;

use WhitePage\Contracts\ColumnInterface;

class TextColumn extends AbstractColumn implements ColumnInterface
{
    private ?array $withRelationship = [];

    public function isRelationship($label = 'name', $id = null)
    {
        $this->withRelationship = [$this->param => $label];
        return $this;
    }

    public function withRelationship()
    {
        return $this->withRelationship;
    }
}
