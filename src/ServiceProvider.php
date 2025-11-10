<?php

namespace WhitePage;

use WhitePage\Facades\WhitePage;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

abstract class ServiceProvider extends LaravelServiceProvider
{
    abstract public function init(Config $config): Config;

    public function register()
    {
        //
    }

    public function boot()
    {
        $config = $this->init(Config::make());

        $sections = [];
        foreach ($config->getSections() as $className) {
            $section = app($className);
            $alias = toPlural($section->getName());
            $sections[$alias] = $section;
        }

        app()->instance('sections', collect($sections));

        $this->app->bind('whitepage', Manager::class);
    }
}
