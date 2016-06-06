<?php

namespace Kun\Generator\Commands;

interface IGenerate
{
    public function className();
    public function typeName();
    public function start();
    public function templatePath();
    public function compileTemplate();
    public function replaceClassName(&$pathTemplate);
    public function replaceNameSpace(&$pathTemplate);
}
