<?php

declare(strict_types=1);

namespace Elph\LaravelModules\Console\Module;

use Elph\LaravelModules\Contract\MakeCommandInterface;
use Elph\LaravelModules\Trait\MakeResourceTrait;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\Make\GeneratorCommand;
use Nwidart\Modules\Traits\ModuleCommandTrait;

class ServiceMakeCommand extends GeneratorCommand implements MakeCommandInterface
{
    use ModuleCommandTrait;
    use MakeResourceTrait;

    private const string OBJECT = 'service';

    protected $argumentName = 'name';

    public function getSuffix(): string
    {
        return 'Service';
    }

    public function getStubContent(): array
    {
        $modelName = $this->getFileName('model');
        $factoryName = $this->getFileName('factory');
        $repositoryName = $this->getFileName('repository');
        $requestName = $this->getFileName('request');

        return [
            'NAMESPACE' => $this->getObjectNamespace(),
            'NAME' => $this->getFileName(),
            'MODEL_NAMESPACE' => $this->getObjectNamespace('model', true),
            'MODEL_NAME' => $modelName,
            'MODEL_VARIABLE' => Str::of($modelName)->camel(),
            'FACTORY_NAMESPACE' => $this->getObjectNamespace('factory', true),
            'FACTORY_NAME' => $factoryName,
            'FACTORY_VARIABLE' => Str::of($factoryName)->camel(),
            'REPOSITORY_NAMESPACE' => $this->getObjectNamespace('repository', true),
            'REPOSITORY_NAME' => $repositoryName,
            'REPOSITORY_VARIABLE' => Str::of($repositoryName)->camel(),
            'REQUEST_NAMESPACE' => $this->getObjectNamespace('request', true),
            'REQUEST_NAME' => $requestName,
            'REQUEST_VARIABLE' => Str::of($requestName)->camel(),
        ];
    }
}
