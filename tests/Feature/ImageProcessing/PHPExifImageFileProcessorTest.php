<?php

namespace Tests\Feature\ImageProcessing;

use App\Infrastructure\ImageProcessing\PHPExifImageFileProcessor;
use Tests\TestCase;

final class PHPExifImageFileProcessorTest extends TestCase
{
    public function testGetExifHeaderData()
    {
        $fileProcessor = new PHPExifImageFileProcessor();
        $result = $fileProcessor->getExifHeaderData(__DIR__ . '/files/image_b.jpg');
        $this->assertCount(7, $result);
        $this->assertArrayHasKey('SectionsFound', $result);
    }

    public function testGetGpsCoordinateForImageWithNoCoordinates()
    {
        $fileProcessor = new PHPExifImageFileProcessor();
        $headerData = [
            $fileProcessor::SECTIONS_FOUND => 'GPS',
            $fileProcessor::GPS_LATITUDE_REF => 'W',
            $fileProcessor::GPS_LATITUDE => ['10/1', '50/1', '30/1'],
            // Longitude missing
        ];
        $result = $fileProcessor->getGpsCoordinate($headerData);
        $this->assertNull($result);
    }

    public function testGetGpsCoordinateForImageWithCoordinates()
    {
        $fileProcessor = new PHPExifImageFileProcessor();
        $headerData = [
            $fileProcessor::SECTIONS_FOUND => 'GPS',
            $fileProcessor::GPS_LATITUDE_REF => 'N',
            $fileProcessor::GPS_LATITUDE => ['10/1', '50/1', '30/1'],
            $fileProcessor::GPS_LONGITUDE_REF => 'W',
            $fileProcessor::GPS_LONGITUDE => ['25/1', '70/1', '10/1'],
        ];
        $result = $fileProcessor->getGpsCoordinate($headerData);
        $this->assertIsObject($result);
    }
}
