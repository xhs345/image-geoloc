<?php

namespace App\Infrastructure\ImageProcessing;

use App\Geolocation\GpsCoordinate\Coordinate;
use App\Geolocation\GpsCoordinate\Latitude;
use App\Geolocation\GpsCoordinate\Longitude;

final class PHPExifImageFileProcessor implements ExifImageFileProcessor
{
    /**
     * Extract the EXIF header data from an image file
     *
     * @param string $imagePath
     *
     * @return array
     */
    public function getExifHeaderData(string $imagePath): array
    {
        $filePointer = \fopen($imagePath, 'rb');
        if (!$filePointer) {
            return []; // Unable to open image for reading
        }
        // Attempt to read the exif headers
        $headers = \exif_read_data($filePointer);

        if (!$headers) {
            return []; // Unable to read exif headers
        }

        return $headers;
    }

    /**
     * Extracts GPS Coordinate from Header Data
     *
     * @param string[] $headerData
     *
     * @return Coordinate|null
     */
    public function getGpsCoordinate(array $headerData): ?Coordinate
    {
        if (!$this->hasGpsCoordinates($headerData)) {
            return null;
        }

        return new Coordinate(
            new Latitude(
                $headerData[self::GPS_LATITUDE_REF],
                $headerData[self::GPS_LATITUDE][0],
                $headerData[self::GPS_LATITUDE][1],
                $headerData[self::GPS_LATITUDE][2]
            ),
            new Longitude(
                $headerData[self::GPS_LONGITUDE_REF],
                $headerData[self::GPS_LONGITUDE][0],
                $headerData[self::GPS_LONGITUDE][1],
                $headerData[self::GPS_LONGITUDE][2]
            )
        );
    }

    /**
     * Expects an array with EXIF data tags and checks if it has a GPS section with all required keys
     *
     * @param array $headerData
     *
     * @return bool
     */
    private function hasGpsCoordinates(array $headerData): bool
    {
        $sections = explode(', ', $headerData[self::SECTIONS_FOUND]);

        return \in_array('GPS', $sections, true)
            && \array_key_exists(self::GPS_LATITUDE_REF, $headerData)
            && \array_key_exists(self::GPS_LATITUDE, $headerData)
            && \array_key_exists(self::GPS_LONGITUDE_REF, $headerData)
            && \array_key_exists(self::GPS_LONGITUDE, $headerData)
            && \count($headerData[self::GPS_LATITUDE]) === 3
            && \count($headerData[self::GPS_LONGITUDE]) === 3;
    }
}
