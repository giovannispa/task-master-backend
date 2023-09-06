<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class MakeRepository extends GeneratorCommand
{
    /**
     * Stubs default path
     */
    const STUB_PATH = __DIR__ . '/Stubs/';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name : Create a repository class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new repository class';

    /**
     * Type of class being generated.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * Rescuing the repository stub.
     *
     * @return string
     */
    protected function getRepositoryStub(): string
    {
        return self::STUB_PATH . 'repository.stub';
    }

    /**
     * Rescuing the interface repository stub.
     *
     * @return string
     */
    protected function getInterfaceRepositoryStub(): string
    {
        return self::STUB_PATH . 'interface-repository.stub';
    }

    /**
     * Execute the console command.
     * @throws FileNotFoundException
     */
    public function handle()
    {
        if ($this->isReservedName($this->getNameInput())) {
            $this->error('The name "' . $this->getNameInput() . '" is reserved by PHP.');

            return false;
        }

        if ($this->alreadyExists($this->getNameInput())) {
            $this->error($this->type . ' already exists!');

            return false;
        }


        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        $this->makeDirectory($path);

        $this->files->put(
            $path,
            $this->sortImports(
                $this->buildRepositoryClass($name)
            )
        );

        $interfaceName = $this->getNameInput() . 'Interface.php';
        $interfacePath = $this->laravel->basePath('app/Interfaces/');

        $this->makeDirectory($interfacePath . $interfaceName);

        $this->files->put(
            $interfacePath . $interfaceName,
            $this->sortImports(
                $this->buildRepositoryInterface($this->getNameInput() . 'Interface')
            )
        );

        $this->info($this->type . ' and Interface created successfully.');
    }

    /**
     * Overriding getStub to satisfy abstract method
     *
     * @return string|void
     */
    protected function getStub()
    {
    }

    /**
     * Build the repository with the given name.
     *
     * @param string $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildRepositoryClass(string $name): string
    {
        $stub = $this->files->get(
            $this->getRepositoryStub()
        );

        $replacements = [
            '{{ class }}' => class_basename($name),
            '{{ model }}' => str_replace("Repository", "", class_basename($name))
        ];

        $stub = str_replace(array_keys($replacements), array_values($replacements), $stub);

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * Build the interface with the given name.
     *
     * @param string $name
     * @return string
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildRepositoryInterface(string $name): string
    {
        $stub = $this->files->get($this->getInterfaceRepositoryStub());

        return $this->replaceNamespace($stub, $name)->replaceClass($stub, $name);
    }

    /**
     * @param $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Repositories';
    }

}
