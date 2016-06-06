<?php
namespace Kun\Generator\Commands;
use Illuminate\Filesystem\Filesystem;
use Kun\Generator\Traits\CommandTrait;
use Kun\Generator\Commands\GenerateCommand;

class GenerateModel extends Generate {
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
        return 'model';
    }

    public function className()
    {
        return $this->object->getObjName('Name');
    }

    public function templatePath()
    {
        return __DIR__ . '/../../templates/src/Models/Model.template';
    }

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
        $tableName = $this->object->option('table');

        if (!$tableName) {
            $tableName = $this->object->getObjName('names');
        }

        $model_fields = $this->object->option('fillable');

        if (!$model_fields) {
            $model_fields = $this->object->option('schema');
        }

        if ($model_fields) {
            $model_fields = explode(',', $model_fields);
            // Add quotes
            $values = '';
            array_walk($model_fields, function(&$value) {
               $value = "'" . trim($value) . "'";
            });
            // CSV format
            $model_fields = implode(',', $model_fields);
        }

        $content = str_replace('{{tableName}}', $tableName, $content);
        $content = str_replace('{{model_fields}}', $model_fields, $content);

        return $this;
    }

    public function start()
    {
        $name = $this->className();
        $packageName = $this->object->option('namespace');
        $packagePath = $this->object->option('dir');

        $modelPath = $this->getPath($name, $packageName, $this->typeName(), $packagePath);

        if (!$this->files->exists($modelPath)) {
            $this->makeFile($modelPath);
        }
        $this->files->put($modelPath, $this->compileTemplate());
        $this->object->info('Model created successfully.');
    }
}
