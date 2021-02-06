<?php

namespace Astrotomic\Hug\Laravel;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class HugServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-hug')
            ->hasConfigFile()
            ->hasTranslations();
    }
}