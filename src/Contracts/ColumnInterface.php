<?php

namespace WhitePage\Contracts;

interface ColumnInterface
{
    public function getExpression();
    public function getName();
    public function getValue($value);
}
