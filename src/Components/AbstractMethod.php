<?php

namespace WhitePage\Components;

use WhitePage\Contracts\SectionInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class AbstractMethod
{
    public const LIST_METHOD = 'index';
    public const CREATE_METHOD = 'store';
    public const EDIT_METHOD = 'update';
    public const VIEW_METHOD = 'show';
    public const DELETE_METHOD = 'destroy';
    public const FORM_METHOD = 'form';
    public const TABLE_METHOD = 'table';
    public const RELATIONSHIP_METHOD = 'relationship';

    public const METHODS = [
        self::LIST_METHOD,
        self::CREATE_METHOD,
        self::DELETE_METHOD,
    ];

    public const METHODS_PAGE = [
        self::EDIT_METHOD,
        self::VIEW_METHOD,
    ];

    public const METHODS_BACKEND = [
        self::FORM_METHOD,
        self::TABLE_METHOD,
        self::RELATIONSHIP_METHOD,
    ];

    public function __construct(
        protected Request $request,
        protected SectionInterface $section,
        protected string $method,
        protected string|int|null $id
    ) {
    }

    protected function getComponentsPath()
    {
        return sprintf(
            '%s.%s',
            AbstractSection::RESOURCES_NAMESPACE,
            'components'
        );
    }

    protected function getFormPath()
    {
        return sprintf(
            '%s.%s',
            $this->getComponentsPath(),
            'form'
        );
    }

    protected function getRelationshipPath()
    {
        return sprintf(
            '%s.%s',
            $this->getComponentsPath(),
            'relationship'
        );
    }

    protected function getTablePath()
    {
        return sprintf(
            '%s.%s',
            $this->getComponentsPath(),
            'table'
        );
    }
}
