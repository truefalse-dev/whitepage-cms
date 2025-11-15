<?php

namespace WhitePage;

use WhitePage\Commands\GenerateSection;
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
        $this->loadViewsFrom(dirname(__DIR__) . '/resources/views', 'whitepage');

        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');

        $config = $this->init(Config::make());

        $sections = [];
        foreach ($config->getSections() as $className) {
            $section = app($className);
            $alias = toPlural($section->getName());
            $sections[$alias] = $section;
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateSection::class,
            ]);
        }

        app()->instance('sections', collect($sections));

        $this->app->bind('whitepage', Manager::class);
    }
}
