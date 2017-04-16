<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 08/04/2017
 * Time: 09:53
 */

namespace Tertere\Test;

use Symfony\Component\Filesystem\Filesystem;
use Psr\Log\LoggerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\VarDumper\VarDumper;
use Tertere\FsClient\Clients\LocalClient;
use Tertere\FsClient\Fs\Local\LocalConfig;

class LocalClientTest extends TestCase
{
    private $testDir = "./tmp/localClientTest";
    private $testDirRenamed = "./tmp/localClientTestRenamed";

    public function testConstruct() {
        $paths = $this->setupTest();
        $client = new LocalClient(
            $this->getConfig(),
            $this->getLogger()
        );

        var_dump(
            $client->browse("/")
        );
    }

    private function getConfig() {
        return new LocalConfig([
            "rootDir" => dirname(__FILE__),
            "tmpDir" => "/tmp",
            "defaultPermissions" => "2755"
        ]);
    }

    private function getLogger() {
        return $this->createMock(LoggerInterface::class);
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
