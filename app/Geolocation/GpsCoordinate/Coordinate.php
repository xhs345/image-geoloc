<?php

namespace App\Geolocation\GpsCoordinate;

/**
 * Value Object for a GPS Coordinate
 */
final class Coordinate
{
    /**
     * @var Latitude
     */
    private $latitude;
    /**
     * @var Longitude
     */
    private $longitude;

    public function __construct(Latitude $latitude, Longitude $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    /**
     * @return Latitude
     */
    public function getLatitude(): Latitude
    {
        return $this->latitude;
    }

    /**
     * @return Longitude
     */
    public function getLongitude(): Longitude
    {
        return $this->longitude;
    }
}
