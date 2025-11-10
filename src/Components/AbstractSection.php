<?php

namespace WhitePage\Components;

use WhitePage\Builders\FormBuilder;
use WhitePage\Builders\ListBuilder;
use WhitePage\Contracts\SectionInterface;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractSection implements SectionInterface
{
    protected static ?string $modelClass = null;

    private ?Model $model;

    public const RESOURCES_NAMESPACE = 'whitepage';

    private $class;

    public function __construct()
    {
        $this->class = get_called_class();
    }

    /**
     * @return SectionInterface
     */
    private function initClass(): SectionInterface
    {
        return new $this->class;
    }

    public function getModelClass()
    {
        return new static::$modelClass;
    }

    /**
     * @param $request
     * @return ListBuilder
     */
    public function initList($request): ListBuilder
    {
        return $this->initClass()
            ->list(new ListBuilder($request, $this));
    }

    /**
     * @param $request
     * @param $model
     * @return FormBuilder
     */
    public function initForm($request, $model): FormBuilder
    {
        return $this->initClass()
            ->form(new FormBuilder($request, $this, $model));
    }

    public function initModel($id)
    {
        $this->model = $this->getModelClass()->find($id);
        return $this->model;
    }

    /**
     * getters
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): string
    {
        return toPlural($this->name);
    }

    public function getModelId()
    {
        return $this->model?->id;
    }

    public function getSectionResourcesPath()
    {
        return sprintf(
            '%s.sections.%s',
            AbstractSection::RESOURCES_NAMESPACE,
            $this->getAlias()
        );
    }
}
