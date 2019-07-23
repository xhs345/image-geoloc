<?php

namespace App\Infrastructure\DataExport;

use Illuminate\Contracts\View\Factory as ViewFactory;

final class HtmlExporter extends AbstractExporter
{
    /**
     * @var ViewFactory
     */
    private $viewFactory;

    public function __construct(ViewFactory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
        parent::__construct('html');
    }

    /**
     * Takes array and outputs as basic HTML file
     *
     * @param array $data
     *
     * @return bool FALSE on error
     */
    public function export(array $data): bool
    {
        $view = $this->viewFactory->make('export.html-export-template', ['data' => $data]);
        $filePointer = \fopen($this->getExportFileName(), 'wb');
        $writeResult = \fwrite($filePointer, $view);
        \fclose($filePointer);

        return $writeResult;
    }
}
