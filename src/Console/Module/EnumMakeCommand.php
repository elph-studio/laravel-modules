<?php

declare(strict_types=1);

namespace Elph\LaravelModules\Console\Module;

use Elph\LaravelModules\Contract\MakeCommandInterface;
use Elph\LaravelModules\Trait\MakeResourceTrait;
use Nwidart\Modules\Commands\Make\GeneratorCommand;
use Nwidart\Modules\Traits\ModuleCommandTrait;

class EnumMakeCommand extends GeneratorCommand implements MakeCommandInterface
{
    use ModuleCommandTrait;
    use MakeResourceTrait;

    private const string OBJECT = 'enum';

    protected $argumentName = 'name';

    public function getSuffix(): string|null
    {
        return 'Enum';
    }

    public function getStubContent(): array
    {
        return [
            'NAMESPACE' => $this->getObjectNamespace(),
            'NAME' => $this->getFileName(),
        ];
    }
}
