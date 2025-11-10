<?php

namespace WhitePage\Components\Columns;

use Carbon\Carbon;
use WhitePage\Contracts\ColumnInterface;

class DateColumn extends AbstractColumn implements ColumnInterface
{
    public function getValue($value)
    {
        return Carbon::parse($value)->toFormattedDateString();
    }
}
