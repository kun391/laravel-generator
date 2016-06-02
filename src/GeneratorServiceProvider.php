<?php

namespace Kun\Generator;

use Illuminate\Support\ServiceProvider;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Set views path
        // $this->loadViewsFrom(__DIR__ . '/resources/views', 'laravel-generator');
        //
        // $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'laravel-generator');
        //
        // // // Publish migrations
        // $this->publishes([
        //   __DIR__.'/database' => base_path('database'),
        // ], 'migrations');
        //
        // $this->publishes([
        //   __DIR__.'/resources/lang' => base_path('resources/lang/vendor/laravel-generator')
        // ], 'translations');
        // // Publish views
        // $this->publishes([
        //     __DIR__ . '/resources/views' => base_path('resources/views/vendor/laravel-generator'),
        // ], 'views');
        //
        // $this->publishes([
        //     __DIR__.'/config/category.php' => config_path('category.php'),
        // ]);
        //
        // if (!$this->app->routesAreCached()) {
        //     require __DIR__.'/Http/routes.php';
        // }
        //
        // $this->publishes([
        //     __DIR__.'/../public/assets' => public_path('vendor/kun-category'),
        // ], 'public');
        //
        // \View::share('formCategory', \Config::get('category.aliases.form.alias_name'));
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerGenerator();
    }

    /**
	 * Register the make:generate.
	 */
	private function registerGenerator()
	{
		$this->app->singleton('command.generator.run', function ($app) {
			return $app['Kun\Generator\Commands\GenerateCommand'];
		});
		$this->commands('command.generator.run');
	}
}
