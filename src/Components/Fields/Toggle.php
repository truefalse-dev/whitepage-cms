<?php

namespace WhitePage\Components\Fields;

use WhitePage\Contracts\FieldInterface;

class Toggle extends AbstractField implements FieldInterface
{
    public function getType(): string
    {
        return 'toggle';
    }

    public function rule(): array
    {
        return [];
    }

    public function getValue()
    {
        if ($this->value === null) {
            return true;
        }

        return $this->value;
    }
}
