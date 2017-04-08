<?php

namespace Tertere\Test\Fs\Local;

use \PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Tertere\FsClient\Fs\Local\LocalDirectory;

class LocalDirectoryTest extends TestCase
{
    private $testDir = "./tmp/directoryTest";
    private $testDirRenamed = "./tmp/directoryTestRenamed";

    public function testGetDirs()
    {
        $paths = $this->setupTest();
        $oDirectory = new LocalDirectory($this->testDir);
        $dirs = $oDirectory->getDirs();
        $this->assertTrue(is_array($dirs));
        $this->assertTrue(count($dirs) > 0);

        foreach ($dirs as $dir) {
            self::assertTrue($dir->isDir());
        }
    }

    public function testGetLinks()
    {
        $paths = $this->setupTest();
        $oDirectory = new LocalDirectory($this->testDir);
        $links = $oDirectory->getLinks();
        $this->assertTrue(is_array($links));
        $this->assertTrue(count($links) > 0);

        foreach ($links as $link) {
            self::assertTrue($link->isLink());
        }
    }

    public function testGetFiles()
    {
        $paths = $this->setupTest();
        $oDirectory = new LocalDirectory($this->testDir);
        $files = $oDirectory->getLinks();
        $this->assertTrue(is_array($files));
        $this->assertTrue(count($files) > 0);

        foreach ($files as $file) {
            self::assertTrue($file->isFile());
        }
    }

    public function testGetExcludedFiles()
    {
        $paths = $this->setupTest();
        $oDirectory = new LocalDirectory($this->testDir);
        $exFiles = $oDirectory->getExcludedFiles();
        $this->assertTrue(is_array($exFiles));
    }
//
//    public function testCreateSubDir()
//    {
//    }
//
    public function testRename()
    {
        $paths = $this->setupTest();
        $oDirectory = new LocalDirectory($this->testDir);
        $oDirectory->rename($this->testDirRenamed);
        self::assertTrue(file_exists($oDirectory->getPath()));
        $oDirectory->delete();
    }

    private function setupTest()
    {
        $ofs = new Filesystem();
        $ofs->remove($this->testDir);
        $ofs->remove($this->testDirRenamed);

        mkdir($this->testDir, 0777, true);

        $paths = [
            "file" => realpath($this->testDir) . "/testFile.txt",
            "dir" => realpath($this->testDir) . "/testDir",
            "link" => realpath($this->testDir) . "/testLink"
        ];

        touch($paths["file"]);
        mkdir($paths["dir"]);
        symlink($paths["file"], $paths["link"]);

        return $paths;
    }
}
