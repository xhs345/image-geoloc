<?php

namespace App\Infrastructure\DataExport;

abstract class AbstractExporter implements Exporter
{
    /**
     * @var string
     */
    protected $fileName;

    protected $extension;

    public function __construct(string $extension)
    {
        $this->fileName = 'export';
        $this->extension = $extension;
    }

    public function getExportFileName(): string
    {
        return $this->fileName . '.' . $this->extension;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setFileName(string $fileName): void
    {
        $this->fileName = $fileName;
    }
}
