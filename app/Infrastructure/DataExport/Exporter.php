<?php
namespace App\Infrastructure\DataExport;

interface Exporter
{
    public function export(array $data): bool;

    public function getExportFileName(): string;

    public function setFileName(string $fileName): void;
}
