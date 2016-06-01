<?php

class GeneralTest extends TestCase {

    public function testInValidCommand()
    {
        try {
            $artisan = Artisan::call('generator:run');
        } catch (Exception $e) {
            $this->assertEquals('Not enough arguments (missing: "name, dir, namespace").', $e->getMessage());
        }

        try {
            $artisan = Artisan::call('generator:run', ['name' => 'admins']);
        } catch (Exception $e) {
            $this->assertEquals('Not enough arguments (missing: "dir, namespace").', $e->getMessage());
        }

        try {
            $artisan = Artisan::call('generator:run', ['dir' => './packages']);
        } catch (Exception $e) {
            $this->assertEquals('Not enough arguments (missing: "name, namespace").', $e->getMessage());
        }

        try {
            $artisan = Artisan::call('generator:run', ['namespace' => 'Kun\Admins']);
        } catch (Exception $e) {
            $this->assertEquals('Not enough arguments (missing: "name, dir").', $e->getMessage());
        }
    }

    public function testValidCommand()
    {
        $artisan = Artisan::call('generator:run', ['name' => 'admins', 'dir' => './packages', 'namespace' => 'Kun\Admins']);
        $this->assertTrue(file_exists('./packages/'. 'Admins' . '/src/Http/Controllers/AdminController.php'));
        $this->assertTrue(file_exists('./packages/'. 'Admins' . '/src/Http/Requests/AdminStoreRequest.php'));
        $this->assertTrue(file_exists('./packages/'. 'Admins' . '/src/Http/Requests/AdminUpdateRequest.php'));
        $this->assertTrue(file_exists('./packages/'. 'Admins' . '/src/Models/Admin.php'));
        $this->assertTrue(file_exists('./packages/'. 'Admins' . '/src/routes.php'));
        $this->assertTrue(file_exists('./packages/'. 'Admins' . '/src/AdminServiceProvider.php'));
    }
}
