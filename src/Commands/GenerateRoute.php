<?php
namespace Kun\Generator\Commands;
use Illuminate\Filesystem\Filesystem;
use Kun\Generator\Traits\CommandTrait;
use Kun\Generator\Commands\GenerateCommand;

class GenerateRoute {
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
        return 'route';
    }

    public function templatePath()
    {
        return __DIR__ . '/../../templates/src/routes.template';
    }

    public function compileTemplate()
    {
        $content = $this->files->get($this->templatePath());
        $this->replaceVariable($content);
        return $content;
    }

    /**
     * Renomeia o endereço do Model para o controller
     *
     * @param $stub
     * @return $this
     */
    public function replaceVariable(&$content)
    {
        $resources = $this->object->getObjName('names');
        $name = $this->object->getObjName('Name') . 'Controller';
        $controllerPath = $this->object->option('namespace') . '\Http\Controllers\\' . $name;
        $content = str_replace('{{controllerPath}}', $controllerPath, $content);
        $content = str_replace('{{resources}}', $resources, $content);
        return $this;
    }

    public function start()
    {
        $packageName = $this->object->option('namespace');
        $packagePath = $this->object->option('dir');

        $modelPath = $this->getPath('routes', $packageName, $this->typeName(), $packagePath);

        if (!$this->files->exists($modelPath)) {
            $this->makeFile($modelPath);
        }
        $this->files->put($modelPath, $this->compileTemplate(__DIR__ . '/../../templates/src/routes.template'));
        $this->object->info('Route created successfully.');
    }
}
