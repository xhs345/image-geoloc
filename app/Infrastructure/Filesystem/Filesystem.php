<?php

namespace App\Infrastructure\Filesystem;

interface Filesystem
{
    /**
     * This function generates a list of all files in the chosen directory and all subdirectories,
     * and returns them.
     *
     * @param string $rootDir
     * @param array $allData
     *
     * @return array
     */
    public function scanDirectories(string $rootDir, array $allData = []): array;

    /**
     * Filters non-image files from the array
     *
     * @param array $allFiles Filepath of each file
     *
     * @return array Filtered list
     */
    public function getImageFiles(array $allFiles): array;
}
