<?php

namespace App\Console\Commands\Module;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateModule extends Command
{
    protected $signature = 'make:module {name}';
    protected $description = 'Generate a new app module structure';

    public function handle(): void
    {
        $module_name = $this->argument('name');
        $prefix = str($module_name)->lower()->plural();
        $module_path = app_path("Modules/$module_name");

        if(file_exists($module_path)) {
            $this->error("Module '$module_name' already exists");
            return;
        }

        $directories = [
            'Http/Controllers',
            'Http/Requests',
            'Dtos',
            'Models',
            'Repositories',
            'Services',
            'routes',
            'migrations'
        ];

        foreach ($directories as $directory) {
            File::makeDirectory("$module_path/$directory", 0755, true);
        }

        File::makeDirectory("$module_path/routes/api", 0755, true);

        File::put("$module_path/Http/Controllers/{$module_name}Controller.php", $this->generateController($module_name));
        File::put("$module_path/Models/$module_name.php", $this->generateModel($module_name));
        File::put("$module_path/Services/{$module_name}Service.php", $this->generateService($module_name));
        File::put("$module_path/routes/api/$prefix.php", $this->generateRoutes($module_name));
        File::put("$module_path/{$module_name}ServiceProvider.php", $this->generateModuleServiceProvider($module_name));

        $this->info("Blog module '$module_name' has been generated successfully. Please, register module service provider");
    }

    protected function generateController($module_name): string
    {
        return <<<STUB
        <?php

        namespace App\Modules\\$module_name\\Http\Controllers;

        use App\Http\Controllers\Controller;
        use Illuminate\Http\Request;

        class {$module_name}Controller extends Controller
        {

            public function __construct(

            )
            {
            }

            public function index(Request \$request)
            {

            }
        }
        STUB;
    }

    protected function generateModel($module_name): string
    {
        return <<<STUB
        <?php

        namespace App\Modules\\$module_name\\Models;

        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Model;

        class $module_name extends Model
        {
            use HasFactory;

            protected \$fillable = [
                '',
            ];
        }
        STUB;
    }

    protected function generateService($module_name): string
    {
        return <<<STUB
        <?php

        namespace App\Modules\\$module_name\\Services;

        class {$module_name}Service
        {
            public function __construct(

            )
            {
            }
        }

        STUB;
    }

    protected function generateModuleServiceProvider($module_name): string
    {
        $routes_file = str($module_name)->lower()->plural();

        return <<<STUB
        <?php

        namespace App\Modules\\$module_name;

        use Illuminate\Support\Facades\Route;
        use Illuminate\Support\ServiceProvider;

        class {$module_name}ServiceProvider extends ServiceProvider
        {
            public function boot(): void
            {
               \$this->registerRoutes();
            }

            protected function registerRoutes(): void
            {
                Route::middleware('api')
                    ->prefix('api')
                    ->namespace('App\\\\Modules\\\\$module_name\\\\Controllers\\\\')
                    ->group(__DIR__ . '/../$module_name/routes/api/$routes_file.php');
            }
        }

        STUB;
    }

    protected function generateRoutes(string $module_name): string
    {
        $prefix = str($module_name)->lower()->plural();

        return <<<STUB
        <?php

        use App\Modules\\$module_name\Http\Controllers\\{$module_name}Controller;
        use Illuminate\Routing\Router;

        Route::group(['middleware' => [], 'prefix' => '$prefix'], function (Router \$router) {
            \$router->get('', [{$module_name}Controller::class, 'index'])->name('$prefix.index');
        });

        STUB;

    }
}
