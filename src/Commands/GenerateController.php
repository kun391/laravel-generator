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

    public function start()
    {
        // Cria o nome do arquivo do controller // TweetController
        $name = $this->object->getObjName('Name') . 'Controller';
        $packageName = $this->object->getObjName('Names');
        $packagePath = $this->object->argument('dir');
        // Verifica se o arquivo existe com o mesmo o nome
        if ($this->files->exists($path = $this->getPath($name, $packageName, $this->typeName(), $packagePath))) {
            return $this->object->error($name . ' already exists!');
        }
        $this->makeFile($path);
        // Cria a pasta caso nao exista
        // Grava o arquivo
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
        $content = $this->files->get(__DIR__ . '/../../templates/src/Http/Controllers/Controller.template');
        $this->replaceClassName($content)
            ->replaceNameSpace($content)
            ->replaceModelName($content);
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
        $className = $this->object->getObjName('Name') . 'Controller';
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

    public function replaceModelName(&$content)
    {
        $model_name_uc = $this->object->getObjName('Name');
        $model_name = $this->object->getObjName('name');
        $model_names = $this->object->getObjName('names');
        $content = str_replace('{{model_name_class}}', $model_name_uc, $content);
        $content = str_replace('{{model_name_var_sgl}}', $model_name, $content);
        $content = str_replace('{{model_name_var}}', $model_names, $content);
        return $this;
    }
}
