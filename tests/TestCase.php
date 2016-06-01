<?php

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getPackageProviders($app)
    {
        return [Kun\Generator\GeneratorServiceProvider::class];
    }

    public function setUp()
    {
        parent::setUp();
        if (File::isDirectory('packages')) {
            File::deleteDirectory('packages');
        }
    }

    public function testChicken()
    {
        $this->assertTrue(true);
    }
}
