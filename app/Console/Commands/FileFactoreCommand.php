<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;

abstract class FileFactoreCommand extends Command
{
    protected $file;
    public function __construct(Filesystem $file)
    {
        parent::__construct();
        $this->file = $file;
    }

    abstract public function SetStubName(): string;
    abstract public function SetpathFile(): string;
    abstract public function SetSuffix(): string;

    public function stubPath()
    {
        $stupname = $this->SetStubName();
        return __DIR__ . "/../../../stubs/$stupname.stub";
    }

    public function singleClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    public function makedir($path)
    {
        $this->file->makeDirectory($path, 077, true, true);
        return $path;
    }

    public function stubContent($stubpath, $stubvariables)
    {
        $content = file_get_contents($stubpath);
        foreach ($stubvariables as $key => $value) {
            $contents = str_replace('$' . $key, $value, $content);
        }
        return $contents;
    }

    public function getpath()
    {
        $pathfile = $this->SetpathFile();
        $suffix = $this->SetSuffix();
        return base_path($pathfile) . $this->singleClassName($this->argument('classname')) ."{$suffix}". ".php";
    }

    public function stubVariables()
    {
        return [
            'NAME' => $this->singleClassName($this->argument('classname')),
        ];
    }

    public function handle()
    {
        $path = $this->getpath();
        $this->makedir(dirname($path));
        if($this->file->exists($path)) {
            $this->info('this file already exists');
        } else {
            $content = $this->stubContent($this->stubPath(), $this->stubVariables());
            $this->file->put($path, $content);
            $this->info('this file has been created');
        }
    }
}
