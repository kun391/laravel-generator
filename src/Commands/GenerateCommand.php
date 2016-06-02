<?php

namespace Kun\Generator\Commands;

use Kun\Generator\Traits\CommandTrait;
use Illuminate\Console\AppNamespaceDetectorTrait;
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
    use AppNamespaceDetectorTrait, CommandTrait;
    /**
     * The console command name!
     *
     * @var string
     */
    protected $name = 'generator:run';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a modules with bootstrap 3';
    /**
     * Meta information for the requested migration.
     *
     * @var array
     */
    protected $meta;
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
        // $this->makeViewLayout();
        // $this->makeViews();
        $this->composer->dumpAutoloads();
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
            ['dir', InputArgument::REQUIRED, 'The directory of the package. (Ex: ./packages)'],
            ['namespace', InputArgument::REQUIRED, 'The namespace of the package. (Ex: Kun/Generator)'],
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
            ['schema', 's', InputOption::VALUE_REQUIRED, 'Schema to generate scaffold files. (Ex: --schema="title:string")', null],
            ['form', 'f', InputOption::VALUE_OPTIONAL, 'Use Illumintate/Html Form facade to generate input fields', false]
        ];
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
        new GenerateStoreRequest($this, $this->files);
        new GenerateUpdateRequest($this, $this->files);
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
