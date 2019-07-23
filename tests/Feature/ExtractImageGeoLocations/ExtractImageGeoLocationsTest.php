<?php

namespace Tests\Feature\ExtractImageGeoLocations;

use Tests\TestCase;

final class ExtractImageGeoLocationsTest extends TestCase
{
    public function tearDown(): void
    {
        // Cleanup files that might've been created during test runs
        is_file('export.csv') ? unlink('export.csv') : null;
        is_file('export.html') ? unlink('export.html') : null;
        parent::tearDown();
    }

    public function testExtractImageGeoLocationsWithDefaultOptionsSuccess()
    {
        $this->artisan('image:extract-geoloc')
            ->expectsOutput('Exported data to export.csv')
            ->assertExitCode(0);
        $this->assertFileExists('export.csv');
    }

    public function testExtractImageGeoLocationsWithPathParameterSuccess()
    {
        $dir = __DIR__ . '/gps_images';
        $this->artisan('image:extract-geoloc '. $dir)
            ->expectsOutput('Exported data to export.csv')
            ->assertExitCode(0);
        $this->assertFileExists('export.csv');
    }

    public function testExtractImageGeoLocationsPathWithNoFiles()
    {
        $this->artisan('image:extract-geoloc /tmp')
            ->expectsOutput('Nothing to export')
            ->assertExitCode(0);
        $this->assertFileNotExists('export.csv');
    }

    public function testExtractImageGeoLocationsExportToHtml()
    {
        $dir = __DIR__ . '/gps_images';
        $this->artisan("image:extract-geoloc {$dir} --export=html")
            ->expectsOutput('Exported data to export.html')
            ->assertExitCode(0);
        $this->assertFileExists('export.html');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExtractImageGeoLocationsWithInvalidExportOptionThrowsException()
    {
        $dir = __DIR__ . '/gps_images';
        $this->artisan("image:extract-geoloc {$dir} --export=foo");
    }
}
