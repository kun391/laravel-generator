<?php

namespace Kun\Generator\Commands;

use Kun\Generator\Commands\IGenerate;

class Generate implements IGenerate
{
    public function className() {}
    public function typeName() {}
    public function start() {}
    public function templatePath() {}
    public function compileTemplate() {}
    public function replaceClassName(&$pathTemplate) {}
    public function replaceNameSpace(&$pathTemplate) {}
}
