<?php

namespace Tests\Feature\DataExport;

use App\Infrastructure\DataExport\HtmlExporter;
use Tests\TestCase;

final class HtmlExporterTest extends TestCase
{
    public function testDataIsExportedToHtmlFile()
    {
        $htmlExporter = $this->app->make(HtmlExporter::class);
        $result = $htmlExporter->export($this->exportData());
        $this->assertFileExists($htmlExporter->getExportFileName());
        $this->assertTrue($result);
        unlink($htmlExporter->getExportFileName());
    }

    public function exportData(): array
    {
        return [
            ['path/to/image/a.jpg', '41°24’12.2″N', '2°10’26.5″E'],
            ['path/to/image/b.jpg', '56°24’12.4″N', '28°11’36.5″E']
        ];
    }
}
