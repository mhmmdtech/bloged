<?php

namespace Tests\Feature\Console;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\File;

class MakeTraitTest extends TestCase
{

    public function test_creates_a_new_trait_file(): void
    {
        // Arrange
        $traitName = 'ExampleTrait';
        $traitPath = app_path('Traits') . DIRECTORY_SEPARATOR . $traitName . '.php';

        // Clean up the trait file if it already exists
        if (File::exists($traitPath)) {
            File::delete($traitPath);
        }

        // Act
        $output = $this->artisan('make:trait', ['name' => $traitName]);

        // Assert
        $output->expectsOutputToContain($traitPath . ' was created successfully!');
    }

    public function test_displays_an_error_if_trait_file_already_exists(): void
    {
        // Arrange
        $traitName = 'ExampleTrait';
        $traitPath = app_path('Traits') . '/' . $traitName . '.php';

        // Create a dummy trait file to simulate an existing file
        File::put($traitPath, 'Dummy content');

        // Act
        $output = $this->artisan('make:trait', ['name' => $traitName]);

        // Assert
        $output->expectsOutputToContain('Unable to create a file with this name because a file with this name already exists');
    }

    public function test_prompts_for_trait_name_if_not_provided(): void
    {
        // Arrange
        $traitName = 'ExampleTrait';
        $traitPath = app_path('Traits') . '/' . $traitName . '.php';

        // Clean up the trait file if it already exists
        if (File::exists($traitPath)) {
            File::delete($traitPath);
        }

        // Mock the user input using the command's `ask` method
        $this->artisan('make:trait')
            ->expectsQuestion('What is Trait\'s name?', $traitName);

        // Assert
        $this->assertTrue(File::exists($traitPath));
    }

    public function test_displays_an_error_if_no_name_provided(): void
    {
        // Mock the user input using the command's `ask` method
        $this->artisan('make:trait')
            ->expectsQuestion('What is Trait\'s name?', "")
            ->expectsOutputToContain('Not enough arguments (missing: "name").');

    }
}