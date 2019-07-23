<?php

namespace App\Geolocation\GpsCoordinate;

use Webmozart\Assert\Assert;

final class Longitude extends AbstractLatitudeLongitude
{
    public function __construct(string $ref, string $degrees, string $minutes, string $seconds)
    {
        Assert::true(\in_array($ref, ['E', 'W']));
        parent::__construct($ref, $degrees, $minutes, $seconds);
    }
}
