<?php

declare(strict_types=1);

namespace Elph\LaravelModules\Service;

use Nwidart\Modules\Generators\ModuleGenerator as BaseModuleGenerator;
use Nwidart\Modules\Support\Config\GenerateConfigReader;

class ModuleGenerator extends BaseModuleGenerator
{
    public function generateResources(): void
    {
        $name = $this->getName();

        $this->generateResource('model', $name, parameters: ['model' => $name]);
        $this->generateResource('faker', $name, $name);
        $this->generateResource('request', $name, $name);
        $this->generateResource('response', $name, $name);
        $this->generateResource('seeder', $name, $name);
        $this->generateResource('provider', $name, $name . 'ServiceProvider', parameters: ['--master' => true]);
        $this->generateResource('repository', $name, $name);
        $this->generateResource('factory', $name, $name);
        $this->generateResource('service', $name, $name);
        $this->generateResource('controller_api', $name, $name, 'module:make-controller');
        $this->generateResource('migration', $name, $name, 'module:make-migration');
        $this->generateResource('enum', $name, $name);
    }

    private function generateResource(
        string $type,
        string $module,
        string|null $name = null,
        string|null $command = null,
        array $parameters = []
    ): void {
        if (GenerateConfigReader::read($type)->generate() !== true) {
            return;
        }

        $arguments['module'] = $module;

        if ($name !== null) {
            $arguments['name'] = $name;
        }

        if (count($parameters) > 0) {
            $arguments += $parameters;
        }

        $command ??= 'module:make-' . $type;

        $this->console->call($command, $arguments);
    }

    protected function fireEvent(string $event): void
    {
        // Do nothing
    }
}
