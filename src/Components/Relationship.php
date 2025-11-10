<?php

namespace WhitePage\Components;

use App\Facades\WhitePage;
use App\Traits\Makeable;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

class Relationship
{
    use Makeable;

    private $relationship;
    private Collection $items;

    public function __construct(
        private string $param
    ) {
    }

    public function columns($array)
    {
        return $this;
    }

    public function setRelationship($relationship): static
    {
        $this->relationship = $relationship;
        $this->items = $relationship->getResults();
        return $this;
    }

    public function getItems(): Collection
    {
        return $this->items ?? collect();
    }

    public function getHref(): string
    {
        return href(WhitePage::SERVICE_ROOT_PREFIX, '');
    }

    public function getName(): string
    {
        return $this->param;
    }
}
