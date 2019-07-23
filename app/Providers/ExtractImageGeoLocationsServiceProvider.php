<?php

namespace App\Providers;

use App\Console\Commands\ExtractImageGeoLocations;
use App\Infrastructure\Filesystem\Filesystem;
use App\Infrastructure\Filesystem\FilesystemService;
use App\Infrastructure\ImageProcessing\ExifImageFileProcessor;
use App\Infrastructure\ImageProcessing\PHPExifImageFileProcessor;
use Illuminate\Support\ServiceProvider;

class ExtractImageGeoLocationsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(ExtractImageGeoLocations::class)
            ->needs(Filesystem::class)
            ->give(FilesystemService::class);
        $this->app->when(ExtractImageGeoLocations::class)
            ->needs(ExifImageFileProcessor::class)
            ->give(PHPExifImageFileProcessor::class);
    }
}
