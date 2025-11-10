<?php

namespace WhitePage\Cms\Methods;

use WhitePage\Components\AbstractList;

class IndexMethod extends AbstractList
{
    public function get()
    {
        $title = $this->getTitle();

        $list = sprintf('%s.list', $this->section->getSectionResourcesPath());
        return view($list, compact('title'));
    }

    public function post()
    {
        $sectionList = $this->listBuilder();
        $action = $sectionList->getAction();

        $columns = $sectionList->getColumns();
        $filters = $sectionList->getFilters();
        $titles = $sectionList->getTitles();

        $builder = $sectionList->getQueryBuilder();

        $limit = $this->request->input('limit', self::DEFAULT_LIMIT);

        $list = $builder->paginate($limit);

        $rows = $list->map(function ($row) use ($columns) {
            return collect($row->getAttributes())->map(
                fn ($value, $field) => $columns->get($field)->getValue($value)
            );
        });

        $pages = (int) ceil($list->total() / $list->perPage());
        $links = $pages > 1 ? range(1, $pages) : [];

        return view($this->getTablePath(), compact(
            'rows',
            'titles',
            'filters',
            'links',
            'action',
            'limit'
        ));
    }
}
