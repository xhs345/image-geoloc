<?php

return [
    // Used by ExporterFactory to bind the format to the implementation
    'factory' => [
        'csv' => App\Infrastructure\DataExport\CsvExporter::class,
        'html' => App\Infrastructure\DataExport\HtmlExporter::class,
    ]
];
