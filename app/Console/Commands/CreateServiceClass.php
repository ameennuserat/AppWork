<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

class CreateServiceClass extends FileFactoreCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'this command for create servicd class pattern';

    /**
     * Execute the console command.
     */

    public function SetStubName(): string
    {
        return "servicepattern";
    }

    public function SetpathFile(): string
    {
        return "App\\Service\\";
    }
    public function SetSuffix(): string
    {
        return "Service";
    }

    public function handle()
    {
        parent::handle();

        return $this->info($this->getpath());
    }
}
