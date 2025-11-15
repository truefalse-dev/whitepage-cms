<?php

namespace WhitePage\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class BaseCommand extends Command
{
    protected $upperName;
    protected $lowerName;
    protected $lowerPluralName;

    public function __construct(
        protected readonly Filesystem $files,
    ) {
        parent::__construct();
    }

    protected function initNames()
    {
        $name = $this->argument('name');

        if (empty($name)) {
            $name = $this->ask('What is your name?');
        }

        $this->upperName = Str::ucfirst($name);
        $this->lowerName = Str::lower($name);
        $this->lowerPluralName = toPlural($this->lowerName);
    }
}
