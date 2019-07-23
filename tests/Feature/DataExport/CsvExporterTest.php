<?php

namespace Tests\Feature\DataExport;

use App\Infrastructure\DataExport\CsvExporter;
use Tests\TestCase;

final class CsvExporterTest extends TestCase
{
    public function testDataIsExportedToCsvFile()
    {
        $csvExporter = new CsvExporter();
        $result = $csvExporter->export($this->exportData());
        $this->assertFileExists($csvExporter->getExportFileName());
        $this->assertTrue($result);
        $this->assertEquals($this->expectedOutput(), \file_get_contents($csvExporter->getExportFileName()));
        unlink($csvExporter->getExportFileName());
    }

    private function exportData(): array
    {
        return [
            ['path/to/image/a.jpg', '41°24’12.2″N', '2°10’26.5″E'],
            ['path/to/image/b.jpg', '56°24’12.4″N', '28°11’36.5″E']
        ];
    }

    private function expectedOutput(): string
    {
        return 'path/to/image/a.jpg,41°24’12.2″N,2°10’26.5″E
path/to/image/b.jpg,56°24’12.4″N,28°11’36.5″E
';
    }
}
