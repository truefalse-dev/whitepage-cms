<?php

namespace WhitePage\Builders;

use Illuminate\Support\Str;

class NavigationBuilder
{
    public static function menu()
    {
        return app('sections')->map(function ($section) {
            return (object) [
                'name' => $section->getName(),
                'label' => Str::ucfirst($section->getName()),
            ];
        });
    }
}
