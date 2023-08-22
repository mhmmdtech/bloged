<?php

namespace Tests\Feature\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\File;

class MakeEnumTest extends TestCase
{
    public function test_creates_a_new_enum_file(): void
    {
        // Arrange
        $enumName = 'ExampleEnum';
        $enumPath = app_path('Enums') . DIRECTORY_SEPARATOR . $enumName . '.php';

        // Clean up the enum file if it already exists
        if (File::exists($enumPath)) {
            File::delete($enumPath);
        }

        // Act
        $output = $this->artisan('make:enum', ['name' => $enumName]);

        // Assert
        $output->expectsOutputToContain($enumPath . ' was created successfully!');
    }

    public function test_displays_an_error_if_enum_file_already_exists(): void
    {
        // Arrange
        $enumName = 'ExampleEnum';
        $enumPath = app_path('Enums') . '/' . $enumName . '.php';

        // Create a dummy enum file to simulate an existing file
        File::put($enumPath, 'Dummy content');

        // Act
        $output = $this->artisan('make:enum', ['name' => $enumName]);

        // Assert
        $output->expectsOutputToContain('Unable to create a file with this name because a file with this name already exists');
    }

    public function test_prompts_for_enum_name_if_not_provided(): void
    {
        // Arrange
        $enumName = 'ExampleEnum';
        $enumPath = app_path('Enums') . '/' . $enumName . '.php';

        // Clean up the enum file if it already exists
        if (File::exists($enumPath)) {
            File::delete($enumPath);
        }

        // Mock the user input using the command's `ask` method
        $this->artisan('make:enum')
            ->expectsQuestion('What is Enum\'s name?', $enumName);

        // Assert
        $this->assertTrue(File::exists($enumPath));
    }

    public function test_displays_an_error_if_no_name_provided(): void
    {
        // Mock the user input using the command's `ask` method
        $this->artisan('make:enum')
            ->expectsQuestion('What is Enum\'s name?', "")
            ->expectsOutputToContain('Not enough arguments (missing: "name").');

    }
}