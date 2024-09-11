<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateInterfaceCommand extends FileFactoreCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {classname}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

     public function SetStubName(): string
     {
         return "interface";
     }

     public function SetpathFile(): string
     {
         return "App\\interfaces\\";
     }
     public function SetSuffix(): string
     {
         return "Interface";
     }

     function handle()
     {
        parent::handle();
        return $this->info($this->getpath());
     }

}
