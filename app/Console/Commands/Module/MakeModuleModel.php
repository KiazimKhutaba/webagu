<?php

namespace App\Console\Commands\Module;

use Illuminate\Console\Command;

class MakeModuleModel extends Command
{
    protected $signature = 'make:module-model {module} {model}';
    protected $description = 'Create a new model in the specified module';

    public function handle()
    {
        $module = $this->argument('module');
        $name = $this->argument('model');

        $path_to_file = "App\\Modules\\$module\\Models\\$name";

        $params = [
            'name' => $path_to_file,
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

        $this->call('make:model', $params);

        $this->info("Model '$name' created successfully in module '$module'.");
    }
}
