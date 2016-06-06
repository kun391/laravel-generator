<?php

namespace Kun\Generator\Commands;

use Kun\Generator\Traits\CommandTrait;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Kun\Generator\Commands\GenerateController;
use Kun\Generator\Commands\GenerateStoreRequest;
use Kun\Generator\Commands\GenerateUpdateRequest;
use Kun\Generator\Commands\GenerateMigration;
use Kun\Generator\Commands\GenerateProvider;
use Kun\Generator\Commands\GenerateConfig;
use Kun\Generator\Commands\GenerateRoute;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
class GenerateCommand extends Command
{
    use CommandTrait;
    /**
     * The console command name!
     *
     * @var string
     */
    protected $name = 'generator:run';

    /**
     * @var Composer
     */
    private $composer;
    /**
     * Views to generate
     *
     * @var array
     */
    private $views = ['index', 'create', 'show', 'edit'];
    /**
     * Store name from Model
     * @var string
     */
    private $nameModel = '';

    private $packageName = '';

    private $packagePath = '';
    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * @param Composer $composer
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();
        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('Command generator:run fire');
        // Start Scaffold
        $this->info('Configuring ' . $this->getObjName("Name") . '...');
        $this->meta['action'] = 'create';
        $this->meta['var_name'] = $this->getObjName("name");
        $this->meta['table'] = $this->getObjName("names"); // Store table name
        // Generate files
        $this->makeController();
        $this->makeModel();
        $this->makeRequest();
        $this->makeMigration();
        $this->makeConfig();
        $this->makeRoute();
        $this->makeProvider();
        $this->updateComposer();

    }
    /**
     * Generate the desired migration.
     */
    protected function makeMigration()
    {
        new GenerateMigration($this, $this->files);
    }

    /**
     * Generate the desired migration.
     */
    protected function makeProvider()
    {
        new GenerateProvider($this, $this->files);
    }
    /**
     * Generate an Eloquent model, if the user wishes.
     */
    protected function makeModel()
    {
        new GenerateModel($this, $this->files);
    }

    /**
     * Generate an Eloquent model, if the user wishes.
     */
    protected function makeRoute()
    {
        new GenerateRoute($this, $this->files);
    }

    /**
     * Generate the desired migration.
     */
    protected function makeConfig()
    {
        new GenerateConfig($this, $this->files);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the package. (Ex: Admins)'],
        ];
    }
    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['namespace', 'ns', InputOption::VALUE_REQUIRED, 'The namespace of the package. (Ex: Kun\Generator)'],
            ['dir', 'di',InputOption::VALUE_REQUIRED, 'The directory of the package. (Ex: ./packages)'],
            ['schema', 's', InputOption::VALUE_OPTIONAL, 'Schema to generate packages. (Ex: --schema="title:string")', null],
            ['table', 't', InputOption::VALUE_OPTIONAL, 'Table name. (Ex: --table="admins")', null],
            ['fillable', 'a', InputOption::VALUE_OPTIONAL, 'fields to show in model. (Ex: --fillable="id,status")', null],
        ];
    }

    protected function updateComposer()
    {
        $arVendor =  explode('\\', $this->option('namespace'));
        $requirement = '"psr-4": {
            "'.$arVendor[0].'\\\\'.$arVendor[1].'\\\\": "packages/'.$arVendor[0].'/'.$arVendor[1].'/src",';
        $providerClass = 'Kun\Generator\GeneratorServiceProvider::class,
        '.$arVendor[0].'\\'.$arVendor[1].'\\'.$arVendor[1].'ServiceProvider::class,';

        $composer = $this->files->get(getcwd().'/composer.json');

        if ($this->files->isDirectory(getcwd().'/config')) {
            $appConfig = $this->files->get(getcwd().'/config/app.php');
            $appConfig = str_replace('Kun\Generator\GeneratorServiceProvider::class,', $providerClass, $appConfig);
            $this->files->put(getcwd().'/config/app.php', $appConfig);
        }

        $composer = str_replace('"psr-4": {', $requirement, $composer);
        $this->files->put(getcwd().'/composer.json', $composer);
        $this->info('Adding package to composer and app...');
        $this->composer->dumpAutoloads();
    }
    /**
     * Make a Controller with default actions
     */
    private function makeController()
    {
        new GenerateController($this, $this->files);
    }

    /**
     * Make a Controller with default actions
     */
    private function makeRequest()
    {
        (new GenerateStoreRequest($this, $this->files))->start();
        (new GenerateUpdateRequest($this, $this->files))->start();
    }
    /**
     * Generate names
     *
     * @param string $config
     * @return mixed
     * @throws \Exception
     */
    public function getObjName($config = 'Name')
    {
        $names = [];
        $args_name = $this->argument('name');
        // Name[0] = Tweet
        $names['Name'] = str_singular(ucfirst($args_name));
        // Name[1] = Tweets
        $names['Names'] = str_plural(ucfirst($args_name));
        // Name[2] = tweets
        $names['names'] = str_plural(strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $args_name)));
        // Name[3] = tweet
        $names['name'] = str_singular(strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $args_name)));
        if (!isset($names[$config])) {
            throw new \Exception("Position name is not found");
        };
        return $names[$config];
    }
}
