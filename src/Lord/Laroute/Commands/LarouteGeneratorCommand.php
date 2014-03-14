<?php

namespace Lord\Laroute\Commands;

use Config;
use Illuminate\Console\Command;
use Lord\Laroute\Routes\Collection as Routes;
use Symfony\Component\Console\Input\InputOption;
use Lord\Laroute\Routes\Exceptions\ZeroRoutesException;
use Lord\Laroute\Generators\GeneratorInterface as Generator;

class LarouteGeneratorCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:laroute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a laravel routes file';

    /**
     * An array of all the registered routes.
     *
     * @var \Lord\Laroute\Routes\Collection
     */
    protected $routes;

    /**
     * The generator instance.
     *
     * @var \Lord\Laroute\Generators\GeneratorInterface
     */
    protected $generator;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Routes $routes, Generator $generator)
    {
        parent::__construct();

        $this->routes    = $routes;
        $this->generator = $generator;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        try {
            $filePath = $this->generator->compile(
                $this->getTemplatePath(),
                $this->getTemplateData(),
                $this->getFileGenerationPath()
            );

            return $this->info("Created: {$filePath}");
        } catch (NoRoutesException $e) {
            $this->error($e->getMessage());
        }
    }

    /**
     * Get path to the template file.
     *
     * @return string
     */
    protected function getTemplatePath()
    {
        return Config::get('laroute::config.template');
    }

    /**
     * Get the data for the template.
     *
     * @return array
     */
    protected function getTemplateData()
    {
        $namespace = $this->getOptionOrConfig('namespace');
        $routes    = $this->routes->toJSON();

        return compact('namespace', 'routes');
    }


    /**
     * Get the path where the file will be generated.
     *
     * @return string
     */
    protected function getFileGenerationPath()
    {
        $path     = $this->getOptionOrConfig('path');
        $filename = $this->getOptionOrConfig('filename');

        return "{$path}/{$filename}.js";
    }

    /**
     * Get an option value either from console input, or the config files.
     */
    protected function getOptionOrConfig($key)
    {
        if ($option = $this->option($key)) {
            return $option;
        }

        return Config::get("laroute::config.{$key}");
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array(
                'path',
                'p',
                InputOption::VALUE_OPTIONAL,
                sprintf('Path to the javscript assets directory (default: "%s")', Config::get('laroute::config.path'))
            ),
            array(
                'filename',
                'f',
                InputOption::VALUE_OPTIONAL,
                sprintf('Filename of the javascript file (default: "%s")', Config::get('laroute::config.filename'))
            ),
            array(
                'namespace',
                null,
                InputOption::VALUE_OPTIONAL, sprintf('Javascript namespace for the functions (think _.js) (default: "%s")', Config::get('laroute::config.namespace'))
            ),
        );
    }
}
