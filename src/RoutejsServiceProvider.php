<?php

namespace Noonic\Routejs;

use Illuminate\Support\ServiceProvider;
use Noonic\Routejs\Console\Commands\RoutejsGeneratorCommand;
use Noonic\Routejs\Routes\Collection as Routes;

class RoutejsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $source = $this->getConfigPath();
        $this->publishes([$source => config_path('routejs.php')], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $source = $this->getConfigPath();
        $this->mergeConfigFrom($source, 'routejs');

        $this->registerGenerator();

        $this->registerCompiler();

        $this->registerCommand();
    }

    /**
     * Get the config path
     *
     * @return string
     */
    protected function getConfigPath()
    {
        return realpath(__DIR__.'/../config/routejs.php');
    }

    /**
     * Register the generator.
     *
     * @return void
     */
    protected function registerGenerator()
    {
        $this->app->bind(
            'Noonic\Routejs\Generators\GeneratorInterface',
            'Noonic\Routejs\Generators\TemplateGenerator'
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
            'Noonic\Routejs\Compilers\CompilerInterface',
            'Noonic\Routejs\Compilers\TemplateCompiler'
        );
    }

    /**
     * Register the command
     *
     * @return void
     */
    protected function registerCommand()
    {
        $this->app->singleton(
            'command.laroute.generate',
            function ($app) {
                $config     = $app['config'];
                $routes     = new Routes($app['router']->getRoutes(), $config->get('routejs.filter', 'all'), $config->get('routejs.action_namespace', ''));
                $generator  = $app->make('Noonic\Routejs\Generators\GeneratorInterface');

                return new RoutejsGeneratorCommand($config, $routes, $generator);
            }
        );

        $this->commands('command.laroute.generate');
    }
}
