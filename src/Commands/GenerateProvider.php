<?php
namespace Kun\Generator\Commands;
use Illuminate\Filesystem\Filesystem;
use Kun\Generator\Traits\CommandTrait;
use Kun\Generator\Commands\GenerateCommand;

class GenerateProvider extends Generate {
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
        return 'provider';
    }

    public function  compileTemplate()
    {
        $content = $this->files->get(__DIR__ . '/../../templates/src/ServiceProvider.template');
        $this->replaceClassName($content)
            ->replaceNameSpace($content)
            ->replaceVariable($content);
        return $content;
    }
    /**
     * Replace the class name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    public function replaceClassName(&$content)
    {
        $className = $this->object->getObjName('Name') . 'ServiceProvider';
        $content = str_replace('{{class}}', $className, $content);
        return $this;
    }

    /**
     * Renomeia o endereço do Model para o controller
     *
     * @param $stub
     * @return $this
     */
    public function replaceNameSpace(&$content)
    {
        $nameSpace = $this->object->argument('namespace');
        $content = str_replace('{{namespace}}', $nameSpace, $content);
        return $this;
    }

    /**
     * Renomeia o endereço do Model para o controller
     *
     * @param $stub
     * @return $this
     */
    public function replaceVariable(&$content)
    {
        $packageName = $this->object->getObjName('name') . '-vendor';
        $configName = $this->object->getObjName('name');
        $content = str_replace('{{packageName}}', $packageName, $content);
        $content = str_replace('{{configName}}', $configName, $content);
        return $this;
    }

    public function start()
    {
        $name = $this->object->getObjName('Name') . 'ServiceProvider';
        $packageName = $this->object->getObjName('Names');
        $packagePath = $this->object->argument('dir');

        $modelPath = $this->getPath($name, $packageName, $this->typeName(), $packagePath);

        if (!$this->files->exists($modelPath)) {
            $this->makeFile($modelPath);
        }
        $this->files->put($modelPath, $this->compileTemplate(__DIR__ . '/../../templates/src/ServiceProvider.template'));
        $this->object->info('Provider created successfully.');
    }
}
