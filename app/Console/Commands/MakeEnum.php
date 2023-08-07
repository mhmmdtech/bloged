<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\File;
use Illuminate\Console\Command;

class MakeEnum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:enum {name? : the name of Enum}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new enum';

    /**
     * Return the stub file path
     * @return string
     *
     */
    public function getStub()
    {
        return File::get(__DIR__ . '/Stubs/make-enum.stub');
    }

    /**
     * Write content to Enum file
     * @return string
     *
     */
    public function setContent($fileDirectory, $fileName, $mainContent)
    {
        File::isDirectory($fileDirectory) || File::makeDirectory($fileDirectory);

        $filePath = $fileDirectory . '/' . $fileName;

        if (File::exists($filePath)) return false;

        File::put($filePath, $mainContent);

        return $filePath;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name') ?? $this->ask('What is Enum\'s name?');

        $stubContent = $this->getStub();

        $mainContent = str_replace('{{ name }}', $name, $stubContent);

        $fileDirectory = app_path('Enums');

        $fileName = $name . '.php';

        $result = $this->setContent($fileDirectory, $fileName, $mainContent);

        if ($result === false) {
            $this->error('Unable to create a file with this name because a file with this name already exists');
            return;
        }

        $this->info($result . ' was created successfully!');
    }
}
