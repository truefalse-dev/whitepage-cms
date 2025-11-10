<?php

namespace WhitePage\Components\Filters;

use Illuminate\Support\Collection;

class SelectFilter extends AbstractFilter
{
    protected Collection $options;
    protected array $relationship = [];

    public function relationship($label = 'name', $id = null)
    {
        $this->relationship = [$this->param => $label];
        $this->param = $id ?? sprintf('%s_id', $this->param);
        return $this;
    }

    public function options($options)
    {
        $this->options = $options instanceof Collection ? $options : collect($options);
        return $this;
    }

    public function getOptions(): array
    {
        $options = $this->options->map(function ($value, $key) {
            return ['id' => $key, 'label' => $value];
        })->values();

        $options->prepend([
            'id' => 0,
            'label' => 'Non selected',
        ]);

        return $options->toArray();
    }

    public function getRelationship()
    {
        return $this->relationship;
    }

    public function getType(): string
    {
        return 'select';
    }
}
