<?php

namespace WhitePage\Components\Fields;

use WhitePage\Contracts\FieldInterface;
use WhitePage\Contracts\RetationshipInterface;
use Illuminate\Support\Collection;

class Select extends AbstractField implements FieldInterface
{
    protected Collection $options;
    private ?array $withRelationship = [];
    protected bool $isInteger = false;

    public function rule(): array
    {
        $rules = [];

        if ($this->isRequired) {
            $rules[] = 'required';
        }

        $type = 'string';

        if ($this->isInteger) {
            $type = 'integer';
        }

        $rules[] = $type;
        $rules[] = 'nullable';

        return $rules;
    }

    public function integer()
    {
        $this->isInteger = true;
        return $this;
    }

    public function options($options)
    {
        $this->options = $options instanceof Collection ? $options : collect($options);
        return $this;
    }

    public function isRelationship($label = 'name', $id = null)
    {
        $this->withRelationship = [$this->param => $label];
        return $this;
    }

    public function getOptions(): array
    {
        $options = $this->options->map(function ($value, $key) {
            return ['id' => $key, 'label' => $value];
        })->values();

        $options->prepend([
            'id' => '',
            'label' => 'Non selected',
        ]);

        return $options->toArray();
    }

    public function withRelationship()
    {
        return $this->withRelationship;
    }

    public function getType(): string
    {
        return 'select';
    }
}
