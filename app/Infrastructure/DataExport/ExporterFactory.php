<?php

namespace App\Infrastructure\DataExport;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;

class ExporterFactory
{
    /**
     * @var Application
     */
    private $app;
    /**
     * @var Repository
     */
    private $config;

    public function __construct(Application $app, Repository $config)
    {
        $this->app = $app;
        $this->config = $config;
    }

    public function getExporterImpl(string $format): Exporter
    {
        $implementations = $this->config->get('exporter.factory');
        if (!\array_key_exists($format, $implementations)) {
            throw new \InvalidArgumentException("Format {$format} is not supported");
        }

        return $this->app->make($implementations[$format]);
    }
}
