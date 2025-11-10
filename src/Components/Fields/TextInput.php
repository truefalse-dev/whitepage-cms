<?php

namespace WhitePage\Components\Fields;

use WhitePage\Contracts\FieldInterface;

class TextInput extends AbstractField implements FieldInterface
{
    protected bool $isEmail = false;
    protected bool $isInteger = false;

    public function rule(): array
    {
        $rules = [];

        if ($this->isRequired) {
            $rules[] = 'required';
        }

        $type = 'string';

        if ($this->isEmail) {
            $type = 'email';
        }

        if ($this->isInteger) {
            $type = 'integer';
        }

        $rules[] = $type;

        return $rules;
    }

    public function integer()
    {
        $this->isInteger = true;
        return $this;
    }

    public function email()
    {
        $this->isEmail = true;
        return $this;
    }

    public function getValue()
    {
        if ($this->isInteger && !$this->value) {
            return 0;
        }

        return $this->value;
    }

    public function getType(): string
    {
        return 'input';
    }
}
