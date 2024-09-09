<?php

namespace App\Console\Commands\Module;

use Illuminate\Console\Command;

class MakeModuleController extends Command
{
    protected $signature = 'make:module-controller {module} {controller}';
    protected $description = 'Create a new controller in the specified module';

    public function handle()
    {
        $module = $this->argument('module');
        $name = $this->argument('controller');

        $controllerPath = "App\\Modules\\$module\\Controllers\\$name";

        $params = [
            'name' => $controllerPath,
        ];

        $options = array_slice($_SERVER['argv'], 4);

        foreach ($options as $key => $value) {
            if ($value !== null) {
                $params["--$key"] = $value;
            }
            else {
                $params["--$key"] = true;
            }
        }

        $this->call('make:controller', $params);

        $this->info("Controller '$name' created successfully in module '$module'.");
    }
}
