<?php

namespace WhitePage\Backend\Methods;

use WhitePage\Builders\ListBuilder;
use WhitePage\Components\AbstractList;
use WhitePage\Components\Columns\AbstractColumn;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TableMethod extends AbstractList
{
    public function post()
    {
        $this->listBuilder = $this->listBuilder();

        $columns = $this->listBuilder->getColumns()->map(
            fn ($column) => $column->getTitle()
        );

        $filters = $this->listBuilder->getFilters()->mapWithKeys(function($filter) {
            return [
                match($filter->getType()) {
                    'select' => [
                        'type' => $filter->getType(),
                        'name' => $filter->getName(),
                        'label' => $filter->getLabel(),
                        'options' => $filter->getOptions(),
                    ],
                }
            ];
        });

        $builder = $this->listBuilder->getQueryBuilder();

        $draggable = false;
        if (in_array('sort_order', $this->section->getModelClass()->getFillable())) {
            $draggable = true;
            $builder->orderBy('sort_order');
        }

        $paginator = $builder->paginate($this->request->input('limit', self::DEFAULT_LIMIT));

        $list = $this->replacePaginatorItems(
            $paginator,
            $paginator->map(function ($row) {
                return collect($row->getAttributeList($this->section->getName()))->map(
                    function ($value, $field) {
                        /** @var AbstractColumn $column */
                        $column = $this->listBuilder->getColumns()->get($field);
                        return $column?->getValue($value) ?? $value;
                    }
                );
            })
        );

        return json_encode(compact('list',  'columns', 'filters', 'draggable'));
    }

    function replacePaginatorItems(LengthAwarePaginator $paginator, Collection $newItems)
    {
        return new LengthAwarePaginator(
            $newItems->toArray(),
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            ['path' => $paginator->url($paginator->currentPage()), 'query' => request()->query()]
        );
    }

    public function reorder(array $list)
    {
        DB::transaction(function () use ($list) {
            foreach ($list as $item) {
                $model = $this->section->getModelClass()::find($item['id']);
                if ($model) {
                    $model->sort_order = $item['sort_order'];
                    $model->save();
                }
            }
        });
    }

    public function delete(array $list)
    {
        DB::transaction(function () use ($list) {
            foreach ($list as $id) {
                $model = $this->section->getModelClass()::find($id);
                if ($model) {
                    $model->delete();
                }
            }
        });
    }
}
