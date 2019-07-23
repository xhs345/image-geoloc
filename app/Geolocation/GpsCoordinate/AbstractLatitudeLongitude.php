<?php

namespace App\Geolocation\GpsCoordinate;

use Webmozart\Assert\Assert;

abstract class AbstractLatitudeLongitude
{
    /**
     * @var string
     */
    private $ref;
    /**
     * @var float
     */
    private $degrees;
    /**
     * @var float
     */
    private $minutes;
    /**
     * @var float
     */
    private $seconds;

    protected function __construct(string $ref, string $degrees, string $minutes, string $seconds)
    {
        $this->ref = $ref;
        $this->degrees = $this->rationalToFloat($degrees);
        $this->minutes = $this->rationalToFloat($minutes);
        $this->seconds = $this->rationalToFloat($seconds);
    }

    public function toString(): string
    {
        return $this->degrees . '°'
            . $this->minutes . '\''
            . ($this->seconds > 0 ? ($this->seconds . '"') : '')
            . $this->ref;
    }

    /**
     * @return string
     */
    public function getRef(): string
    {
        return $this->ref;
    }

    /**
     * @return float
     */
    public function getDegrees(): float
    {
        return $this->degrees;
    }

    /**
     * @return float
     */
    public function getMinutes(): float
    {
        return $this->minutes;
    }

    /**
     * @return float
     */
    public function getSeconds(): float
    {
        return $this->seconds;
    }

    private function rationalToFloat(string $value): float
    {
        $valueArray = \explode('/', $value);
        Assert::count($valueArray, 2);

        return $valueArray[0] / $valueArray[1];
    }
}
