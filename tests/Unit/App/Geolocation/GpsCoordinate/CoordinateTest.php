<?php

namespace Tests\Unit\App\Geolocation\GpsCoordinate;

use App\Geolocation\GpsCoordinate\Coordinate;
use App\Geolocation\GpsCoordinate\Latitude;
use App\Geolocation\GpsCoordinate\Longitude;
use Tests\TestCase;

final class CoordinateTest extends TestCase
{
    public function testCreatingCoordinate()
    {
        $coordinate = new Coordinate(
            new Latitude('N', '20/1', '50/1', '30/1'),
            new Longitude('E', '20/1', '50/1', '30/1')
        );
        $this->assertIsObject($coordinate);
        $this->assertEquals('N', $coordinate->getLatitude()->getRef());
        $this->assertEquals(20, $coordinate->getLatitude()->getDegrees());
        $this->assertEquals(50, $coordinate->getLatitude()->getMinutes());
        $this->assertEquals(30, $coordinate->getLatitude()->getSeconds());
        $this->assertEquals("20°50'30\"N", $coordinate->getLatitude()->toString());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCreatingCoordinateWithInvalidRefThrowsException()
    {
        new Coordinate(
            new Latitude('E', '20/1', '50/1', '30/1'),
            new Longitude('E', '20/1', '50/1', '30/1')
        );
    }
}
