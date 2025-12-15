<?php

declare(strict_types=1);

namespace Elph\LaravelModules\Provider;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

abstract class ModuleServiceProvider extends ServiceProvider
{
    public function getModuleName(): string
    {
        // phpcs:ignore Generic.PHP.ForbiddenFunctions
        return explode('\\', static::class)[1];
    }

    public function boot(): void
    {
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrations();
    }

    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->getModuleNameLower());
        $sourcePath = module_path($this->getModuleName(), 'View');

        Config::set(
            'view.paths',
            array_merge(Config::get('view.paths'), [$viewPath, $sourcePath]) // phpcs:ignore
        );
    }

    protected function getModuleNameLower(): string
    {
        return Str::of($this->getModuleName())->lower()->snake()->value();
    }

    protected function registerConfig(): void
    {
        $name = $this->getModuleName();
        $lowerName = $this->getModuleNameLower();

        if (File::exists(module_path($name, 'Config')) === false) {
            return;
        }

        $this->publishes([module_path($name, 'Config/config.php') => config_path($lowerName . '.php')], 'config');
        $this->mergeConfigFrom(module_path($name, 'Config/config.php'), $lowerName);

        $files = File::allFiles(module_path($name, 'Config'));
        collect($files)->each(function ($file) use ($name) {
            $this->mergeConfigFrom(
                module_path($name, 'Config/' . $file->getFilename()),
                $file->getBasename('.' . $file->getExtension())
            );
        });
    }

    protected function loadMigrations(): void
    {
        $this->loadMigrationsFrom(module_path($this->getModuleName(), 'Database/Migration'));
        $this->loadMigrationsFrom(module_path($this->getModuleName(), 'Database/Migration/*'));
        $this->loadMigrationsFrom(core_lib_path('Database/Migration'));
    }
}
