<?php
namespace Kun\Generator\Commands;
use Illuminate\Filesystem\Filesystem;
use Kun\Generator\Traits\CommandTrait;
use Kun\Generator\Commands\GenerateCommand;

class GenerateConfig {
    use CommandTrait;
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

    public function templatePath()
    {
        return __DIR__ . '/../../templates/src/config/config.template';
    }

    public function compileTemplate()
    {
        $content = $this->files->get($this->templatePath());
        return $content;
    }

    public function start()
    {
        $name = $this->object->getObjName('name');
        $packageName = $this->object->option('namespace');
        $packagePath = $this->object->option('dir');

        $modelPath = $this->getPath($name, $packageName, $this->typeName(), $packagePath);

        if (!$this->files->exists($modelPath)) {
            $this->makeFile($modelPath);
        }
        $this->files->put($modelPath, $this->compileTemplate());
        $this->object->info('Config created successfully.');
    }
}
