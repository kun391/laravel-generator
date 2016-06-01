<?php
namespace Kun\Generator\Commands;
use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Filesystem\Filesystem;
use Kun\Generator\Traits\CommandTrait;
use Kun\Generator\Commands\GenerateCommand;

class GenerateConfig {
    use AppNamespaceDetectorTrait, CommandTrait;
    protected $object;

    function __construct(GenerateCommand $generateCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->object = $generateCommand;
        $this->start();
    }

    public function typeName()
    {
        return 'config';
    }

    public function compileTemplate()
    {
        $content = $this->files->get(__DIR__ . '/../../templates/src/config/config.template');
        return $content;
    }

    public function start()
    {
        $name = $this->object->getObjName('name');
        $packageName = $this->object->getObjName('Names');
        $packagePath = $this->object->argument('dir');

        $modelPath = $this->getPath($name, $packageName, $this->typeName(), $packagePath);

        if (!$this->files->exists($modelPath)) {
            $this->makeFile($modelPath);
        }
        $this->files->put($modelPath, $this->compileTemplate(__DIR__ . '/../../templates/src/config/config.template'));
        $this->object->info('Config created successfully.');
    }
}
