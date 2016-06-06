<?php

class GeneralTest extends TestCase {

    public function testInValidCommand()
    {
        try {
            $artisan = Artisan::call('generator:run');
        } catch (Exception $e) {
            $this->assertEquals('Not enough arguments (missing: "name").', $e->getMessage());
        }
    }

    public function testValidCommand()
    {
        $artisan = Artisan::call('generator:run', ['name' => 'admins', '--dir' => './packages', '--namespace' => 'Kun\Admins']);
        $artisan = Artisan::call('generator:run', ['name' => 'admins', '--dir' => './packages', '--namespace' => 'Kun\Admins']);
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/Http/Controllers/AdminController.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/Http/Requests/AdminStoreRequest.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/Http/Requests/AdminUpdateRequest.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/Models/Admin.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/routes.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/AdminServiceProvider.php'));
    }

    public function testValidCommandWithOption()
    {
        $artisan = Artisan::call('generator:run', [
            'name' => 'admins', '--dir' => './packages', '--namespace' => 'Kun\Admins',
            '--table' => 'default', '--fillable' => 'id, status'
        ]);
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/Http/Controllers/AdminController.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/Http/Requests/AdminStoreRequest.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/Http/Requests/AdminUpdateRequest.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/Models/Admin.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/routes.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/AdminServiceProvider.php'));
    }

    public function testValidCommandWithOptionValid()
    {
        $artisan = Artisan::call('generator:run', [
            'name' => 'admins', '--dir' => './packages', '--namespace' => 'Kun\Admins',
            '--table' => 'default', '--fillable' => 'id, status',
            '--schema' => 'status:integer, value:string'
        ]);
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/Http/Controllers/AdminController.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/Http/Requests/AdminStoreRequest.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/Http/Requests/AdminUpdateRequest.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/Models/Admin.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/routes.php'));
        $this->assertTrue(file_exists('./packages/'. 'Kun/Admins' . '/src/AdminServiceProvider.php'));
    }
}
