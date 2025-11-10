<?php

namespace WhitePage\Backend\Methods;

use WhitePage\Components\AbstractForm;
use WhitePage\Components\AbstractMethod;
use WhitePage\Contracts\SectionInterface;
use Illuminate\Http\Request;

class RelationshipMethod extends AbstractForm
{
    public function __construct(
        protected Request $request,
        protected SectionInterface $section,
        protected string $method,
        protected string|int|null $id,
        protected string|null $relationship,
    )
    {
        parent::__construct($request, $section, $method, $id);
    }

    public function get()
    {
        $model = $this->section->getModelClass()->find($this->id);
        $relationship = $model->{$this->relationship};
        $id = $this->id;

        return view($this->getRelationshipPath(), compact(
            'id',
            'relationship',
        ));
    }
}
