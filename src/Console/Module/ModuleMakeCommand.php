<?php

declare(strict_types=1);

namespace Elph\LaravelModules\Console\Module;

use Elph\LaravelModules\Service\ModuleGenerator;
use Nwidart\Modules\Commands\Make\ModuleMakeCommand as BaseModuleMakeCommand;
use Nwidart\Modules\Contracts\ActivatorInterface;

class ModuleMakeCommand extends BaseModuleMakeCommand
{
    public function handle(): int
    {
        $success = true;

        collect($this->argument('name'))->each(function ($name) use (&$success) {
            $code = with(new ModuleGenerator($name))
                ->setFilesystem($this->laravel['files'])
                ->setModule($this->laravel['modules'])
                ->setConfig($this->laravel['config'])
                ->setActivator($this->laravel[ActivatorInterface::class])
                ->setConsole($this)
                ->setComponent($this->components)
                ->setForce(false)
                ->setType('api')
                ->setActive(true)
                ->generate();

            // phpcs:ignore SlevomatCodingStandard.ControlStructures.EarlyExit
            if ($code !== E_ERROR) {
                $success = false;
            }
        });

        return $success ? self::SUCCESS : self::FAILURE;
    }
}
