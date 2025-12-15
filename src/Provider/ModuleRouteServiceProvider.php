<?php

declare(strict_types=1);

namespace Elph\LaravelModules\Provider;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class ModuleRouteServiceProvider extends RouteServiceProvider
{
    public function __construct($app, private readonly string $module)
    {
        parent::__construct($app);
    }

    public function map(): void
    {
        $this->mapRoutes('web');
        $this->mapRoutes('api');
    }

    protected function mapRoutes(string $file): void
    {
        $routeFile = module_path($this->module, sprintf('/Route/%s.php', $file));
        if (File::exists($routeFile) === false) {
            return;
        }

        Route::middleware($file)
            ->namespace('Module\\' . $this->module)
            ->group($routeFile);
    }
}
