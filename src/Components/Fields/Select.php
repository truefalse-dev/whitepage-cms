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
    protected bool $hasNonSelected = true;

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

    public function options(Collection|array $options)
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

        if ($this->hasNonSelected) {
            $options->prepend([
                'id' => '',
                'label' => 'Non selected',
            ]);
        }

        return $options->toArray();
    }

    public function boolean($trueLabel = 'Yes', $falseLabel = 'No')
    {
        $this->hasNonSelected = false;
        $this->options = collect([
            1 => $trueLabel,
            0 => $falseLabel,
        ]);
        return $this;
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
