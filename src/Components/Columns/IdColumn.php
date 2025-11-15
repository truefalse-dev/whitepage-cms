<?php

namespace WhitePage\Components\Columns;

use WhitePage\Contracts\ColumnInterface;

class IdColumn extends AbstractColumn implements ColumnInterface
{
    public function __construct()
    {
        $this->param = 'id';
    }
}
