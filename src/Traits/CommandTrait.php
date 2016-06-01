<?php
namespace Kun\Generator\Traits;

use Illuminate\Filesystem\Filesystem;
use Laralib\L5scaffold\Commands\GenerateCommand;

trait CommandTrait {
    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;
    protected $gmCommand;
    /**
     * @param GenerateCommand $generateCommand
     * @param Filesystem $files
     */
    public function __construct(GenerateCommand $generateCommand, Filesystem $files){
        $this->files = $files;
        $this->gmCommand = $generateCommand;
        $this->generateNames($generateCommand);
    }
    /**
     * Get the path to where we should store the controller.
     *
     * @param $file_name
     * @param string $path
     * @return string
     */
    protected function getPath($file_name, $packageName, $fileType, $pathPackage) {
        $baseSrc = $pathPackage . '/' . $packageName . '/src';

        if ($fileType == 'controller') {
            return $baseSrc .'/Http/Controllers/' . $file_name . '.php';
        } elseif($fileType == 'model'){
            return $baseSrc . '/Models/' . $file_name. '.php';
        } elseif($fileType == 'config'){
            return $baseSrc . '/config/' . $file_name. '.php';
        } elseif($fileType == 'route'){
            return $baseSrc . '/' . $file_name. '.php';
        } elseif($fileType == 'migration') {
            return $baseSrc . '/database/migrations/' . $file_name. '.php';
        } elseif($fileType == 'request') {
            return $baseSrc . '/Http/Requests/' . $file_name. '.php';
        } elseif($fileType == 'provider') {
            return $baseSrc . '/' . $file_name. '.php';
        } elseif($fileType == 'view') {
            return '/resources/views/'.$file_name.'/index.blade.php';
        }
    }
    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeFile($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }
}
