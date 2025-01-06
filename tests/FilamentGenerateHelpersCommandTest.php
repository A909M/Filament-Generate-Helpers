<?php

use A909M\FilamentGenerateHelpers\Commands\FilamentGenerateHelpersCommand;
use Illuminate\Console\Application;
use Illuminate\Support\Facades\Artisan;

describe('FilamentGenerateHelpersCommandTest', function () {
    it('does something', function () {
        Application::starting(function ($artisan) {
            $artisan->add(app(FilamentGenerateHelpersCommand::class));
        });

        // Running the command
        Artisan::call('filament-generate-helpers:run');
    });
});
