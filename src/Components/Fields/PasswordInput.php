<?php

namespace WhitePage\Components\Fields;

use WhitePage\Contracts\FieldInterface;
use Illuminate\Database\Eloquent\Model;

class PasswordInput extends AbstractField implements FieldInterface
{
    public function rule(): array
    {
        if ($this->model instanceof Model) {
            $rules[] = 'nullable';
        } else {
            $rules[] = 'required';
        }

        $rules[] = 'string';
        $rules[] = 'min:6';
        //$rules[] = 'confirmed';

        return $rules;
    }

    public function getValue()
    {
        return !$this->model instanceof Model
            ? $this->value
            : null;
    }

    public function getType(): string
    {
        return 'password';
    }
}
