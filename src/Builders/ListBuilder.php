<?php

namespace WhitePage\Builders;

use WhitePage\Facades\WhitePage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ListBuilder
{
    private Collection $columns;
    private Collection $filters;

    public function __construct(
        private $request,
        private $section,
    ) {
    }

    public function table(array $array)
    {
        $this->columns = collect($array)->mapWithKeys(function ($column) {

            if (method_exists($column, 'withRelationship') && $column->withRelationship()) {

                $relationshipName = key($column->withRelationship());
                $label = current($column->withRelationship());

                $relationship = $this->section->getModelClass()->{$relationshipName}();

                if ($relationship instanceof MorphToMany) {

                    // user
                    $parent = $relationship->getParent(); // user
                    $parentTable = $parent->getTable(); // users table

                    $related = $relationship->getRelated(); // roles
                    $relatedTable = $related->getTable(); // roles table
                    $relatedKey = $related->getKeyName();

                    $pivot = $relationship->getTable(); // role_user
                    $pivotForeignKey = $relationship->getForeignPivotKeyName(); // user_id
                    $pivotRelatedKey = $relationship->getRelatedPivotKeyName(); // role_id

                    $morphClass = $relationship->getMorphClass(); // get_class($parent)
                    $morphType = $relationship->getMorphType();

                    $sql = DB::table($this->tableField($relatedTable))
                        ->join($relationship->getTable(), $this->tableField($pivot, $pivotRelatedKey), '=', $this->tableField($relatedTable, $relatedKey))
                        ->whereColumn($this->tableField($pivot, $pivotForeignKey), $this->tableField($parentTable, $relatedKey))
                        ->where($this->tableField($pivot, $morphType), $morphClass)
                        ->selectRaw("GROUP_CONCAT({$this->tableField($relatedTable, $label)} ORDER BY {$this->tableField($relatedTable, $label)} SEPARATOR ', ')")->toRawSql();
                }

                if ($relationship instanceof BelongsTo) {
                    $sql = $relationship->getRelated()
                        ->whereColumn('id', $this->tableField($relationship->getParent()->getTable(), $relationship->getForeignKeyName()))
                        ->select($label)->toSql();
                }

                $column->expression(DB::raw(sprintf('(%s) AS %s', $sql, $column->getName())));
            }

            return [$column->getName() => $column];
        });

        return $this;
    }

    public function filters(array $array)
    {
        $this->filters = collect($array)->mapWithKeys(function ($field) {

            if (method_exists($field, 'getRelationship') && $field->getRelationship()) {

                $relation = key($field->getRelationship());
                $name = current($field->getRelationship());

                /* @var Model $targetClass */
                $targetClass = get_class($this->section->getModelClass()->{$relation}()->getRelated());

                $field->options($targetClass::query()->pluck($name, 'id'));
            }
            return [$field->getName() => $field];
        });

        return $this;
    }

    public function build(): static
    {
        $this->filters->map(
            fn ($filter) => $filter->setInput($this->request->input($filter->getName()))
        );

        return $this;
    }

    public function getAction(string $method = null): string
    {
        return href(WhitePage::CMS_ROOT_PREFIX, $this->section->getName(), $method);
    }

    public function getQueryBuilder(): Builder
    {
        $builder = $this->section->getModelClass()->query();

        $builder->select($this->columns->map(
            fn ($column) => DB::raw($column->getExpression())
        )->toArray());

        foreach ($this->filters as $filter) {
            if (!$filter->getInput()) {
                continue;
            }

            $builder->where($filter->getName(), $filter->getInput());
        }

        return $builder;
    }

    public function getFilters(): Collection
    {
        return $this->filters;
    }

    public function getColumns(): Collection
    {
        return $this->columns;
    }

    private function tableField(string $table, string|null $field = null): string
    {
        return $table . ($field ? '.' . $field : '');
    }
}
