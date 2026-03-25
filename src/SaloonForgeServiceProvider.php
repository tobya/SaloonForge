<?php

namespace Tobya\SaloonForge;

use Spatie\LaravelPackageTools\Package;
use Tobya\SaloonForge\Commands\ForgeRequestCommand;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Tobya\SaloonForge\Commands\SaloonForgeCommand;


class SaloonForgeServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('saloonforge')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_saloonforge_table')
            ->hasCommand(ForgeRequestCommand::class);
    }
}
