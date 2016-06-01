<?php

namespace Kun\Generator\Commands;

interface IGenerate
{
    public function typeName();
    public function start();
    public function compileTemplate();
    public function replaceClassName(&$pathTemplate);
    public function replaceNameSpace(&$pathTemplate);
}
