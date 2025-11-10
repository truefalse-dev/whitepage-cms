<?php

namespace WhitePage\Contracts;

interface FieldInterface
{
    public function getType(): string;
    public function rule(): array;
}
