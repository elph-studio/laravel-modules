<?php

declare(strict_types=1);

namespace Elph\LaravelModules\Contract;

interface MakeCommandInterface
{
    public function getSuffix(): string|null;
    public function getStubContent(): array;
}
