<?php

namespace WhitePage;

class Config
{
    private array $sections;

    public static function make(): static
    {
        return app(static::class);
    }

    public function sections(array $sections): static
    {
        $this->sections = $sections;
        return $this;
    }

    public function getSections(): array
    {
        return $this->sections;
    }
}
