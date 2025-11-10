<?php

namespace WhitePage\Components\Columns;

use WhitePage\Traits\Makeable;
use Illuminate\Support\Str;

class AbstractColumn
{
    use Makeable;

    protected $param;
    protected $expression;
    protected $title;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    public function expression($expression)
    {
        $this->expression = $expression;
        return $this;
    }

    public function getName()
    {
        return $this->param;
    }

    public function getTitle()
    {
        return $this->title ?? Str::ucfirst(str_replace('_', ' ', $this->param));
    }

    public function getExpression()
    {
        return $this->expression ?? $this->param;
    }

    public function getValue($value)
    {
        return $value;
    }
}
