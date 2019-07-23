<?php

namespace App\Infrastructure\DataExport;

final class CsvExporter extends AbstractExporter
{
    public function __construct()
    {
        parent::__construct('csv');
    }

    /**
     * Takes array and outputs as CSV
     *
     * @param array $data
     *
     * @return bool FALSE on error
     */
    public function export(array $data): bool
    {
        $filePointer = \fopen($this->getExportFileName(), 'wb');
        foreach ($data as $fields) {
            $writeResult = \fputcsv($filePointer, $fields);
            if (!$writeResult) {
                return false;
            }
        }
        \fclose($filePointer);

        return true;
    }
}
