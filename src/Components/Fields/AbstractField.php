<?php

namespace WhitePage\Components\Fields;

use WhitePage\Traits\Makeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Str;

abstract class AbstractField
{
    use Makeable;

    protected $param;
    protected bool $isRequired = false;

    protected $id;
    protected $model;
    protected $label;
    protected $value;
    protected $error;
    public $relationship;

    public function __construct($param)
    {
        $this->param = $param;
    }

    public function label($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * setters
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setId($formId)
    {
        $this->id = $formId;
        return $this;
    }

    public function setModel(Model $model)
    {
        $this->model = $model;
        return $this;
    }

    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    public function getName()
    {
        return $this->param;
    }

    public function getLabel()
    {
        return $this->label ?? Str::ucfirst(str_replace('_', ' ', $this->param));
    }

    public function getId()
    {
        return $this->id;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getError()
    {
        return $this->error;
    }

    public function setRelationship(Relation $relationship)
    {
        $this->relationship = $relationship;
        return $this;
    }

    public function required()
    {
        $this->isRequired = true;
        return $this;
    }
}
