<?php

namespace Lord\Laroute;

use Illuminate\Support\ServiceProvider;
use Lord\Laroute\Routes\Collection as Routes;
use Lord\Laroute\Commands\LarouteGeneratorCommand;

class LarouteServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('lord/laroute');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerGenerator();

        $this->registerCompiler();

        $this->registerCommand();
    }

    /**
     * Register the generator.
     *
     * @return void
     */
    protected function registerGenerator()
    {
        $this->app->bind(
            'Lord\Laroute\Generators\GeneratorInterface',
            'Lord\Laroute\Generators\TemplateGenerator'
        );
    }

    /**
     * Register the compiler.
     *
     * @return void
     */
    protected function registerCompiler()
    {
        $this->app->bind(
            'Lord\Laroute\Compilers\CompilerInterface',
            'Lord\Laroute\Compilers\TemplateCompiler'
        );
    }

    /**
     * Register the command
     *
     * @return void
     */
    protected function registerCommand()
    {
        $this->app->bindShared('generate.laroute', function($app)
        {
            $routes    = new Routes($app['router']->getRoutes());
            $generator = $app->make('Lord\Laroute\Generators\GeneratorInterface');

            return new LarouteGeneratorCommand($routes, $generator);
        });

        $this->commands('generate.laroute');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}
