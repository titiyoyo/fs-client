<?php

namespace Titiyoyo\Test\Fs\Local;

use \PHPUnit\Framework\TestCase;
use \Symfony\Component\Filesystem\Filesystem;
use Titiyoyo\FsClient\Fs\Local\LocalItem;
use Titiyoyo\FsClient\Exception\FsClientConfigException;

class LocalItemTest extends TestCase
{
    private $testDir = "./tmp";

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalItem::rename
     */
    public function testFileRename()
    {
        $paths = self::getPaths();
        $localFile = new LocalItem($paths["file"]);
        $localFile->rename($paths["fileRenamed"]);
        $this->assertTrue(true);
        $this->assertTrue($this->checkFileExistence($paths["fileRenamed"]));
        $this->assertFalse($this->checkFileExistence($paths["file"]));

        $this->removeTestFiles();
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalItem::rename
     */
    public function testDirRename()
    {
        $paths = self::getPaths();
        $localDir = new LocalItem($paths["dir"]);
        $localDir->rename($paths["dirRenamed"]);
        $this->assertTrue(true);
        $this->assertTrue($this->checkFileExistence($paths["dirRenamed"]));
        $this->assertFalse($this->checkFileExistence($paths["dir"]));

        $this->removeTestFiles();
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalItem::rename
     */
    public function testLinkRename()
    {
        $paths = self::getPaths();
        $localLink = new LocalItem($paths["link"]);
        $localLink->rename($paths["linkRenamed"]);
        $this->assertTrue(true);
        $this->assertTrue($this->checkFileExistence($paths["linkRenamed"]));
        $this->assertFalse($this->checkFileExistence($paths["link"]));

        $this->removeTestFiles();
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalItem::delete
     */
    public function testDeleteFile()
    {
        $paths = self::getPaths();
        $localFile = new LocalItem($paths["file"]);
        $localFile->delete();
        $this->assertTrue(true);
        $this->assertFalse($this->checkFileExistence($paths["file"]));

        $this->removeTestFiles();
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalItem::delete
     */
    public function testDeleteDir()
    {
        $paths = self::getPaths();
        $localDir = new LocalItem($paths["dir"]);
        $localDir->delete();
        $this->assertTrue(true);
        $this->assertFalse($this->checkFileExistence($paths["dir"]));

        $this->removeTestFiles();
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalItem::delete
     */
    public function testDeleteLink()
    {
        $paths = self::getPaths();
        $localLink = new LocalItem($paths["link"]);
        $localLink->delete();
        $this->assertTrue(true);
        $this->assertFalse($this->checkFileExistence($paths["link"]));

        $this->removeTestFiles();
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalItem::toArray
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalItem::toJson
     */
    public function testFileToArray()
    {
        $paths = self::getPaths();
        $localFile = new LocalItem($paths["file"]);
        $array = $localFile->toArray();
        $this->assertTrue(is_array($array));

        $json = $localFile->toJson();
        $this->assertTrue(is_array(json_decode($json, true)));

        $this->removeTestFiles();
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalItem::toArray
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalItem::toJson
     */
    public function testDirToArray()
    {
        $paths = self::getPaths();
        $localDir = new LocalItem($paths["dir"]);
        $array = $localDir->toArray();
        $this->assertTrue(is_array($array));

        $json = $localDir->toJson();
        $this->assertTrue(is_array(json_decode($json, true)));

        $this->removeTestFiles();
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalItem::toArray
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalItem::toJson
     */
    public function testLinksToArray()
    {
        $paths = self::getPaths();
        $localLink = new LocalItem($paths["link"]);
        $array = $localLink->toArray();
        $this->assertTrue(is_array($array));

        $json = $localLink->toJson();
        $this->assertTrue(is_array(json_decode($json, true)));

        $this->removeTestFiles();
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalItem::getRelativePathTo
     */
    public function testGetRelativePathTo()
    {
        $paths = self::getPaths();
        $localFile = new LocalItem($paths["file"]);
        $relativePath = $localFile->getRelativePathTo(".");
        $this->assertEquals(".", $relativePath);
        $relativePath = $localFile->getRelativePathTo("..");
        $this->assertEquals("..", $relativePath);
        $relativePath = $localFile->getRelativePathTo($paths["file"]);
        $this->assertEquals(".", $relativePath);
        $relativePath = $localFile->getRelativePathTo("./toto/truc");
        $this->assertEquals("./toto/truc", $relativePath);
        $relativePath = $localFile->getRelativePathTo("toto/truc");
        $this->assertEquals("toto/truc", $relativePath);

        $this->removeTestFiles();
    }

    private function checkFileExistence($file)
    {
        return file_exists($file);
    }

    private function getPaths()
    {
        $ofs = new Filesystem();
        $ofs->remove(realpath(dirname($this->testDir)) . "/" . basename($this->testDir));
        mkdir(realpath(dirname($this->testDir)) . "/" . basename($this->testDir), 0777, true);

        $paths = [
            "file" =>  realpath($this->testDir) . "/testFile.txt",
            "dir" => realpath($this->testDir) . "/testDir",
            "link" => realpath($this->testDir) . "/testLink",
            "fileRenamed" =>  realpath($this->testDir) . "/testFileRenamed.txt",
            "dirRenamed" => realpath($this->testDir) . "/testDirRenamed",
            "linkRenamed" => realpath($this->testDir) . "/testLinkRenamed",
        ];

        touch($paths["file"]);
        mkdir($paths["dir"]);
        symlink($paths["file"], $paths["link"]);

        return $paths;
    }

    private function removeTestFiles()
    {
        $ofs = new Filesystem();
        if ($ofs->exists($this->testDir)) {
            $ofs->remove($this->testDir);
        }
    }
}
