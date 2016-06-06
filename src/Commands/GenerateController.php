<?php
namespace Kun\Generator\Commands;
use Illuminate\Filesystem\Filesystem;
use Kun\Generator\Traits\CommandTrait;
use Kun\Generator\Commands\Generate;
use Kun\Generator\Commands\GenerateCommand;
class GenerateController extends Generate
{
    use CommandTrait;
    protected $object;
    protected $files;

    function __construct(GenerateCommand $generateCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->object = $generateCommand;
        $this->start();
    }

    public function typeName()
    {
        return 'controller';
    }

    public function className()
    {
        return $this->object->getObjName('Name') . 'Controller';
    }

    public function templatePath()
    {
        return __DIR__ . '/../../templates/src/Http/Controllers/Controller.template';
    }

    public function start()
    {
        $name = $this->className();
        $packageName = $this->object->option('namespace');
        $packagePath = $this->object->option('dir');

        if ($this->files->exists($path = $this->getPath($name, $packageName, $this->typeName(), $packagePath))) {
            return $this->object->error($name . ' already exists!');
        }
        $this->makeFile($path);

        $this->files->put($path, $this->compileTemplate());
        $this->object->info('Controller created successfully.');
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    public function compileTemplate()
    {
        $content = $this->files->get($this->templatePath());
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
        $className = $this->className();
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
        $nameSpace = $this->object->option('namespace');
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
        $storeRequestClass = (new \Kun\Generator\Commands\GenerateStoreRequest($this->object, $this->files))->className();
        $updateRequestClass = (new \Kun\Generator\Commands\GenerateUpdateRequest($this->object, $this->files))->className();
        $modelClass = (new \Kun\Generator\Commands\GenerateModel($this->object, $this->files))->className();
        $content = str_replace('{{modelClass}}', $modelClass, $content);
        $content = str_replace('{{storeRequestClass}}', $storeRequestClass, $content);
        $content = str_replace('{{updateRequestClass}}', $updateRequestClass, $content);
        return $this;
    }
}
