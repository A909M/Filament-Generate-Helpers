<?php

namespace A909M\FilamentGenerateHelpers;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use A909M\FilamentGenerateHelpers\Commands\FilamentGenerateHelpersCommand;

class FilamentGenerateHelpersServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-generate-helpers')
            ->hasCommand(FilamentGenerateHelpersCommand::class);
    }
}
