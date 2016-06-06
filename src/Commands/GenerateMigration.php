<?php
namespace Kun\Generator\Commands;
use Illuminate\Filesystem\Filesystem;
use Kun\Generator\Traits\CommandTrait;
use Kun\Generator\Traits\SchemaTrait;
use Kun\Generator\Commands\GenerateCommand;

class GenerateMigration {
    use CommandTrait, SchemaTrait;
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

    public function templatePath()
    {
        return __DIR__ . '/../../templates/src/database/migrations/Migration.template';
    }

    public function compileTemplate()
    {
        $content = $this->files->get($this->templatePath());
        $this->replaceClassName($content)
            ->replaceSchema($content)
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
        $nameSpace = $this->object->option('namespace');
        $content = str_replace('{{namespace}}', $nameSpace, $content);
        return $this;
    }

    /**
     * Replace the schema for the stub.
     *
     * @param  string $stub
     * @param string $type
     * @return $this
     */
    protected function replaceSchema(&$content)
    {
        if ($schema = $this->object->option('schema')) {
            $schema = $this->parse($schema);
        }

        $table = $this->object->option('table');

        if (!$table) {
            $table = $this->object->getObjName('names');
        }
        // Create migration fields
        $up = $this->createSchemaForUpMethod($schema, $this->templatePath());
        $down = $this->createSchemaForDownMethod($table);
        $upDown = compact('up', 'down', 'table');
        //
        $content = str_replace(['{{schema_up}}', '{{schema_down}}', '{{table}}'], $upDown, $content);
        return $this;
    }

    public function start()
    {
        $name = $this->name;
        $packageName = $this->object->option('namespace');
        $packagePath = $this->object->option('dir');

        $modelPath = $this->getPath($name, $packageName, $this->typeName(), $packagePath);

        if (!$this->files->exists($modelPath)) {
            $this->makeFile($modelPath);
        }
        $this->files->put($modelPath, $this->compileTemplate());
        $this->object->info('Migrate created successfully.');
    }
}
