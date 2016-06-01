<?php
namespace Kun\Generator\Commands;
use Illuminate\Filesystem\Filesystem;
use Kun\Generator\Traits\CommandTrait;
use Kun\Generator\Commands\GenerateCommand;

class GenerateMigration extends Generate {
    use CommandTrait;
    protected $object;
    protected $name;

    function __construct(GenerateCommand $generateCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->object = $generateCommand;
        $this->name = $this->object->getObjName('Name') . 'Migration' . time();
        $this->start();
    }

    public function typeName()
    {
        return 'migration';
    }

    public function compileTemplate()
    {
        $content = $this->files->get(__DIR__ . '/../../templates/src/database/migrations/Migration.template');
        $this->replaceClassName($content)
            ->replaceNameSpace($content);
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
        $className = $this->name;
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

    public function start()
    {
        $name = $this->name;
        $packageName = $this->object->getObjName('Names');
        $packagePath = $this->object->argument('dir');

        $modelPath = $this->getPath($name, $packageName, $this->typeName(), $packagePath);

        if (!$this->files->exists($modelPath)) {
            $this->makeFile($modelPath);
        }
        $this->files->put($modelPath, $this->compileTemplate(__DIR__ . '/../../templates/src/database/migrations/Migration.template'));
        $this->object->info('Model created successfully.');
    }
}