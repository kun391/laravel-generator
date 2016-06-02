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
    /**
     * Get the path to where we should store the controller.
     *
     * @param $file_name
     * @param string $path
     * @return string
     */
    protected function getPath($file_name, $packageName, $fileType, $pathPackage)
    {
        $baseSrc = $pathPackage . '/' . $packageName . '/src';
        if ($fileType == 'controller') {
            $baseSrc = $baseSrc .'/Http/Controllers/' . $file_name . '.php';
        } elseif ($fileType == 'model') {
            $baseSrc = $baseSrc . '/Models/' . $file_name. '.php';
        } elseif ($fileType == 'config') {
            $baseSrc = $baseSrc . '/config/' . $file_name. '.php';
        } elseif ($fileType == 'route') {
            $baseSrc = $baseSrc . '/' . $file_name. '.php';
        } elseif ($fileType == 'migration') {
            $baseSrc = $baseSrc . '/database/migrations/' . $file_name. '.php';
        } elseif ($fileType == 'request') {
            $baseSrc = $baseSrc . '/Http/Requests/' . $file_name. '.php';
        } elseif ($fileType == 'provider') {
            $baseSrc = $baseSrc . '/' . $file_name. '.php';
        }
        return $baseSrc;
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
