<?php

namespace Tests\Feature\Filesystem;

use App\Infrastructure\Filesystem\FilesystemService;
use Illuminate\Config\Repository;
use Tests\TestCase;

final class FilesystemServiceTest extends TestCase
{
    private static $directories = [
        'dir1',
        'dir2',
        'dir2/sub_dir1'
    ];

    private static $files = [
        'dir1/img_a.jpg',
        'dir2/img_b.jpg',
        'dir2/sub_dir1/img_c.jpg',
        'dir2/sub_dir1/favicon.ico'
    ];

    public function setUp(): void
    {
        parent::setUp();
        foreach (self::$directories as $directory) {
            \mkdir($directory);
        }
        foreach (self::$files as $file) {
            \touch($file);
        }
    }

    public function tearDown(): void
    {
        foreach (self::$files as $file) {
            \unlink($file);
        }
        foreach (\array_reverse(self::$directories) as $directory) {
            \rmdir($directory);
        }
        parent::tearDown();
    }

    public function testScanDirectoriesScansRecursively()
    {
        $repository = $this->createMock(Repository::class);
        $repository->method('get')
            ->with('filesystems.invisibleFileNames')
            ->willReturn(['.', '..']);
        $filesystemService = new FilesystemService($repository);
        $result = $filesystemService->scanDirectories('dir2');
        $this->assertEquals([
            'dir2/img_b.jpg', 'dir2/sub_dir1/favicon.ico', 'dir2/sub_dir1/img_c.jpg',
        ], $result);
    }

    public function testScanDirectoriesIgnoresInvisibleFiles()
    {
        $repository = $this->createMock(Repository::class);
        $repository->method('get')
            ->with('filesystems.invisibleFileNames')
            ->willReturn(['.', '..', 'favicon.ico']);
        $filesystemService = new FilesystemService($repository);
        $result = $filesystemService->scanDirectories('dir2');
        $this->assertEquals(['dir2/img_b.jpg', 'dir2/sub_dir1/img_c.jpg'], $result);
    }

    public function testGetImageFilesFilterImages()
    {
        $repository = $this->createMock(Repository::class);
        $filesystemService = new FilesystemService($repository);
        $files = [
            0 => __DIR__ . '/files/dummy.txt', // not an image
            1 => __DIR__ . '/files/image_a.jpg' // valid image
        ];
        $result = $filesystemService->getImageFiles($files);
        $this->assertEquals([1 => __DIR__ . '/files/image_a.jpg'], $result);
    }
}
