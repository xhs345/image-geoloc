<?php

namespace App\Console\Commands;

use App\Infrastructure\DataExport\ExporterFactory;
use App\Infrastructure\Filesystem\Filesystem;
use App\Infrastructure\ImageProcessing\ExifImageFileProcessor;
use Illuminate\Console\Command;
use Illuminate\Foundation\Application;

class ExtractImageGeoLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:extract-geoloc 
                            {path? : Path from where images should be read. Default is current path.}
                            {--export=csv : Export format. Can be CSV (default) or HTML}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recursively reads all images, extracts their EXIF GPS data (longitude and latitude), 
                              and then writes the name of that image and any GPS co-ordinates it finds to a CSV or HTML file';

    /**
     * @var Application
     */
    private $app;

    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var ExifImageFileProcessor
     */
    private $imageFileProcessor;
    /**
     * @var ExporterFactory
     */
    private $exporterFactory;

    /**
     * Create a new command instance.
     *
     * @param Application $app
     * @param Filesystem $filesystem
     * @param ExifImageFileProcessor $imageFileProcessor
     * @param ExporterFactory $exporterFactory
     */
    public function __construct(
        Application $app,
        Filesystem $filesystem,
        ExifImageFileProcessor $imageFileProcessor,
        ExporterFactory $exporterFactory
    ) {
        parent::__construct();
        $this->app = $app;
        $this->filesystem = $filesystem;
        $this->imageFileProcessor = $imageFileProcessor;
        $this->exporterFactory = $exporterFactory;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        // Fetch images
        $scanDirectory = $this->argument('path') ?: $this->app->basePath();
        $allFiles = $this->filesystem->scanDirectories($scanDirectory);
        $images = $this->filesystem->getImageFiles($allFiles);
        // Extract GPS Coordinates from images
        $results = [];
        foreach ($images as $image) {
            $headerData = $this->imageFileProcessor->getExifHeaderData($image);
            $coordinate = $this->imageFileProcessor->getGpsCoordinate($headerData);
            if ($coordinate === null) {
                $this->warn("Image {$image} does not have GPS data set");
                continue;
            }
            $results[] = [
                $image,
                $coordinate->getLatitude()->toString(),
                $coordinate->getLongitude()->toString()
            ];
        }
        if (\count($results) === 0) {
            $this->warn('Nothing to export');
            return;
        }
        // Export results
        $exportFormat = $this->option('export');
        $exporter = $this->exporterFactory->getExporterImpl($exportFormat);
        $exportResult = $exporter->export($results);
        if (!$exportResult) {
            $this->error('An error occurred when writing the file ' . $exporter->getExportFileName());
        } else {
            $this->info('Exported data to ' . $exporter->getExportFileName());
        }
    }
}
