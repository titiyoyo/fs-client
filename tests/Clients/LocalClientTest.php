<?php

namespace Titiyoyo\Test;

use Symfony\Component\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use Titiyoyo\FsClient\Clients\LocalClient;
use Titiyoyo\FsClient\Fs\Local\LocalConfig;

class LocalClientTest extends TestCase
{
    private string $tmpPath = "./tmp";
    private string $testDir = "./tmp/localClientTest";
    private string $testDirRenamed = "./tmp/localClientTestRenamed";

    /**
     * @covers \Titiyoyo\FsClient\Clients\LocalClient::browse
     */
    public function testBrowse() {
        $this->setupTest();
        $client = new LocalClient(
            new LocalConfig([
                "rootDir" => realpath($this->testDir),
                "tmpDir" => "/tmp",
                "defaultPermissions" => "2755"
            ]),
            $this->createMock(LoggerInterface::class)
        );

        $dir = $client->browse(".");
        $this->assertEquals(realpath($dir->getPath()), realpath($client->getRootDir()));
        $client->mkdir('test2');
        $this->assertTrue(file_exists(realpath($client->getRootDir() . '/test2')));
        $client->mkdir('test2/test3');
        $this->assertTrue(file_exists(realpath($client->getRootDir() . '/test2/test3')));
        $this->assertEquals(2, count($dir->getFiles()));
        $this->assertEquals( 3, count($dir->getItems()));
        $this->assertEquals( 1, count($dir->getDirs()));
        $dir = $client->browse('test2/test3');
        $this->assertEquals($dir->getPath(), realpath($client->getRootDir() . "/test2/test3"));

        $this->removeTestFiles();
    }

    /**
     * @covers \Titiyoyo\FsClient\Clients\LocalClient::browse
     */
    public function testNotAllowed()
    {
        $this->expectException(\Exception::class);

        $this->setupTest();
        $client = new LocalClient(
            new LocalConfig([
                "rootDir" => realpath($this->testDir),
                "tmpDir" => "/tmp",
                "defaultPermissions" => "2755"
            ]),
            $this->createMock(LoggerInterface::class)
        );

        $client->browse("..");

        $this->removeTestFiles();
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

    private function removeTestFiles()
    {
        $ofs = new Filesystem();
        if ($ofs->exists($this->tmpPath)) {
            $ofs->remove($this->tmpPath);
        }
    }
}
