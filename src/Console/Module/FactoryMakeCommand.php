<?php

declare(strict_types=1);

namespace Elph\LaravelModules\Console\Module;

use Elph\LaravelModules\Contract\MakeCommandInterface;
use Elph\LaravelModules\Trait\MakeResourceTrait;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\Make\GeneratorCommand;
use Nwidart\Modules\Traits\ModuleCommandTrait;

class FactoryMakeCommand extends GeneratorCommand implements MakeCommandInterface
{
    use ModuleCommandTrait;
    use MakeResourceTrait;

    private const string OBJECT = 'factory';

    protected $argumentName = 'name';

    public function getSuffix(): string
    {
        return 'Factory';
    }

    public function getStubContent(): array
    {
        $modelName = $this->getFileName('model');
        $requestName = $this->getFileName('request');

        return [
            'NAMESPACE' => $this->getObjectNamespace(),
            'NAME' => $this->getFileName(),
            'MODEL_NAMESPACE' => $this->getObjectNamespace('model', true),
            'MODEL_NAME' => $modelName,
            'MODEL_VARIABLE' => Str::of($modelName)->camel(),
            'REQUEST_NAMESPACE' => $this->getObjectNamespace('request', true),
            'REQUEST_NAME' => $requestName,
            'REQUEST_VARIABLE' => Str::of($requestName)->camel(),
        ];
    }
}
