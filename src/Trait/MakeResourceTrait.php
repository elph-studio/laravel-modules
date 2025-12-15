<?php

declare(strict_types=1);

namespace Elph\LaravelModules\Trait;

use Illuminate\Support\Str;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;

trait MakeResourceTrait
{
    public function getName(): string
    {
        return 'module:make-' . self::OBJECT;
    }

    public function getDescription(): string
    {
        return 'Generate new ' . self::OBJECT . ' for the specified module.';
    }

    public function getDefaultNamespace(): string
    {
        return $this->laravel['modules']->config('paths.generator.' . self::OBJECT . '.path');
    }

    public function getClass(): string
    {
        $name = class_basename($this->argument($this->argumentName));
        $suffix = $this->getSuffix();
        if ($suffix !== null && Str::of($name)->endsWith($suffix)) {
            $name = Str::of($name)
                ->replaceLast($suffix, '')
                ->value();
        }

        return $name;
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of ' . self::OBJECT . ' will be created.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());
        $classPath = GenerateConfigReader::read(self::OBJECT);

        return $path . $classPath->getPath() . '/' . $this->getFileName() . '.php';
    }

    protected function getTemplateContents(): string
    {
        return new Stub($this->getStubName(), $this->getStubContent())->render();
    }

    protected function getStubName(): string
    {
        return '/' . self::OBJECT . '.stub';
    }

    private function getFileName(string|null $type = null): string
    {
        $name = Str::studly($this->argument('name'));

        $suffix = $type !== null ? Str::studly($type) : $this->getSuffix();
        if ($suffix === null || $suffix === 'Model' || Str::of($name)->endsWith($suffix)) {
            return $name;
        }

        if ($suffix === 'AmqpDto') {
            $suffix = 'AmqpDTO';
        }

        return $name . $suffix;
    }

    private function getObjectNamespace(string $type = self::OBJECT, bool $withObject = false): string
    {
        $path = $this->laravel['modules']->config('paths.generator.' . $type . '.path', 'Entities');
        $path = str_replace('/', '\\', $path);

        $namespace = $this->laravel['modules']->config('namespace')
            . '\\'
            . $this->laravel['modules']->findOrFail($this->getModuleName())
            . '\\'
            . $path;

        if ($withObject === false) {
            return $namespace;
        }

        return $namespace . '\\' . $this->getFileName($type);
    }
}
