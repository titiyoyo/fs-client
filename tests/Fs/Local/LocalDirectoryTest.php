<?php

namespace Titiyoyo\Test\Fs\Local;

use \PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Titiyoyo\FsClient\Fs\Local\LocalDirectory;

class LocalDirectoryTest extends TestCase
{
    private $tmpPath = "./tmp";
    private $testDir = "./tmp/directoryTest";
    private $testDirRenamed = "directoryTestRenamed";

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalDirectory::getDirs
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalDirectory::isDir
     */
    public function testGetDirs()
    {
        $this->setupTest();
        $oDirectory = new LocalDirectory($this->testDir);
        $dirs = $oDirectory->getDirs();
        $this->assertTrue(is_array($dirs));
        $this->assertTrue(count($dirs) > 0);

        foreach ($dirs as $dir) {
            self::assertTrue($dir->isDir());
        }

        $this->removeTestFiles();
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalDirectory::getLinks
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalDirectory::isLink
     */
    public function testGetLinks()
    {
        $this->setupTest();
        $oDirectory = new LocalDirectory($this->testDir);
        $links = $oDirectory->getLinks();
        $this->assertTrue(is_array($links));
        $this->assertTrue(count($links) > 0);

        foreach ($links as $link) {
            self::assertTrue($link->isLink());
        }

        $this->removeTestFiles();
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalDirectory::getFiles
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalDirectory::isFile
     */
    public function testGetFiles()
    {
        $this->setupTest();
        $oDirectory = new LocalDirectory($this->testDir);
        $files = $oDirectory->getFiles();
        $this->assertTrue(is_array($files));
        $this->assertTrue(count($files) > 0);

        foreach ($files as $file) {
            self::assertTrue($file->isFile());
        }
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalDirectory::getExcludedFiles
     */
    public function testGetExcludedFiles()
    {
        $this->setupTest();
        $oDirectory = new LocalDirectory($this->testDir);
        $exFiles = $oDirectory->getExcludedFiles();
        $this->assertTrue(is_array($exFiles));

        $this->removeTestFiles();
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalDirectory::rename
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalDirectory::getPath
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalDirectory::delete
     */
    public function testRename()
    {
        $this->setupTest();
        $oDirectory = new LocalDirectory($this->testDir);
        $oDirectory->rename($this->testDirRenamed);
        self::assertEquals(realpath($this->tmpPath . '/' . $this->testDirRenamed), $oDirectory->getPath());
        self::assertTrue(file_exists($oDirectory->getPath()));
        $oDirectory->delete();
        self::assertTrue(!file_exists($oDirectory->getPath()));

        $this->removeTestFiles();
    }

    private function setupTest()
    {
        $ofs = new Filesystem();
        if ($ofs->exists($this->tmpPath)) {
            $ofs->remove($this->tmpPath);
        }

        $ofs->mkdir([$this->testDir]);
        $paths = [
            "file" => realpath($this->testDir) . "/testFile.txt",
            "dir" => realpath($this->testDir) . "/testDir",
            "link" => realpath($this->testDir) . "/testLink"
        ];

        $ofs->touch([$paths["file"]]);
        $ofs->mkdir([$paths["dir"]]);
        $ofs->symlink($paths["file"], $paths["link"]);

        return $paths;
    }

    private function removeTestFiles()
    {
        $ofs = new Filesystem();
        if ($ofs->exists($this->tmpPath)) {
            $ofs->remove($this->tmpPath);
        }
    }
}
