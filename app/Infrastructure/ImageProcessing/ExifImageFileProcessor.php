<?php
namespace App\Infrastructure\ImageProcessing;

use App\Geolocation\GpsCoordinate\Coordinate;

interface ExifImageFileProcessor
{
    public const GPS_LATITUDE_REF = 'GPSLatitudeRef';
    public const GPS_LATITUDE = 'GPSLatitude';
    public const GPS_LONGITUDE_REF = 'GPSLongitudeRef';
    public const GPS_LONGITUDE = 'GPSLongitude';
    public const SECTIONS_FOUND = 'SectionsFound';

    public function getExifHeaderData(string $imagePath): array;

    public function getGpsCoordinate(array $headerData): ?Coordinate;
}
