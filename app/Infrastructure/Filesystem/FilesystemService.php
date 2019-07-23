<?php
namespace App\Infrastructure\Filesystem;

use Illuminate\Config\Repository;

final class FilesystemService implements Filesystem
{
    /** @var string[] */
    private $invisibleFileNames;

    public function __construct(Repository $config)
    {
        $this->invisibleFileNames = $config->get('filesystems.invisibleFileNames', []);
    }

    /**
     * This function generates a list of all files in the chosen directory and all subdirectories,
     * throws them into a NON-multidimentional array and returns them.
     *
     * @param string $rootDir
     * @param array $allData Used by recursive function
     *
     * @return string[]
     *
     * @see Adapted from https://www.php.net/manual/en/function.scandir.php#80057
     */
    public function scanDirectories(string $rootDir, array $allData = []): array
    {
        // run through content of root directory
        $dirContent = \scandir($rootDir);
        foreach($dirContent as $key => $content) {
            // filter all files not accessible
            $path = $rootDir . '/' . $content;
            if(!\in_array($content, $this->invisibleFileNames, false)) {
                if (!\is_readable($path)) {
                    continue;
                }
                // if content is file & readable, add to array
                if (\is_file($path)) {
                    // save file name with path
                    $allData[] = $path;
                    // if content is a directory and readable, add path and name
                } elseif (\is_dir($path)) {
                    // recursive callback to open new directory
                    $allData = $this->scanDirectories($path, $allData);
                }
            }
        }

        return $allData;
    }

    /**
     * Filters non-image files from the array
     *
     * @param array $allFiles Contains filepath of each file
     *
     * @return array Filtered list
     */
    public function getImageFiles(array $allFiles): array
    {
        return \array_filter($allFiles, static function($filePath) {
            // file has to be 12 bytes or larger in order to avoid a "Read error!"
            if (\filesize($filePath) < 12) {
                return false;
            }
            return \exif_imagetype($filePath) !== false;
        });
    }
}
