<?php

namespace WhitePage\Components\Filters;

use WhitePage\Traits\Makeable;
use Illuminate\Support\Str;

class AbstractFilter
{
    use Makeable;

    protected $param;
    protected $input;
    protected $label;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function label($label)
    {
        $this->label = $label;
        return $this;
    }

    public function getName()
    {
        return $this->param;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function getLabel()
    {
        return $this->label ?? Str::ucfirst(str_replace('_', ' ', $this->param));
    }

    /**
     * setters
     */
    public function setInput($value)
    {
        $this->input = $value;
        return $this;
    }
}
