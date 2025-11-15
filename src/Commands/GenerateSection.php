<?php

namespace WhitePage\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateSection extends BaseCommand
{
    protected $signature = 'whitepage:make {name}';
    protected $description = 'Генерує blade та PHP клас за stub із пакету';

    public function handle()
    {
        $this->initNames();

        $resourceListStub = dirname(__DIR__, 2) . '/stubs/resources/list.blade.stub';
        $resourcePageStub = dirname(__DIR__, 2) . '/stubs/resources/page.blade.stub';
        $classSectionStub = dirname(__DIR__, 2) . '/stubs/Section.class.stub';

        if (!$this->files->exists($resourceListStub) || !$this->files->exists($resourcePageStub) || !$this->files->exists($classSectionStub)) {
            $this->error('Stub файли не знайдені у пакеті!');
            return Command::FAILURE;
        }

        $bladeListContent = $this->files->get($resourceListStub);
        $bladePageContent = $this->files->get($resourcePageStub);
        $classSectionContent = $this->replaceStubVariables($this->files->get($classSectionStub), [
            '{{name}}' => $this->upperName,
            '{{lowerName}}' => $this->lowerName,
        ]);

        $destinationResourceList = resource_path("views/whitepage/sections/{$this->lowerPluralName}/list.blade.php");
        $destinationResourcePage = resource_path("views/whitepage/sections/{$this->lowerPluralName}/page.blade.php");
        $destinationSectionClass = app_path("WhitePage/Sections/{$this->upperName}/{$this->upperName}Section.php");

        if ($this->files->exists($destinationResourceList) || $this->files->exists($destinationResourcePage) || $this->files->exists($destinationSectionClass)) {
            $this->error("Blade файл(и) вже існує(ють)");
            return Command::FAILURE;
        }

        $this->putFile($destinationResourceList, $bladeListContent);
        $this->putFile($destinationResourcePage, $bladePageContent);
        $this->putFile($destinationSectionClass, $classSectionContent);

        $this->info("Файли створено!");

        return Command::SUCCESS;
    }

    private function putFile($destination, $content)
    {
        $dir = dirname($destination);
        if (!$this->files->isDirectory($dir)) {
            $this->files->makeDirectory($dir, 0755, true);
        }
        $this->files->put($destination, $content);
    }

    private function replaceStubVariables(string $stub, array $vars): string
    {
        return str_replace(array_keys($vars), array_values($vars), $stub);
    }
}
