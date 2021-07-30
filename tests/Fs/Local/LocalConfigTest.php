<?php

namespace Titiyoyo\Test\Fs\Local;

use \PHPUnit\Framework\TestCase;
use Titiyoyo\FsClient\Fs\Local\LocalConfig;
use Titiyoyo\FsClient\Exception\FsClientConfigException;

class LocalConfigTest extends TestCase
{
    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalConfig::isConfigured
     */
    public function testIsConfigured()
    {
        $config = new LocalConfig($this->getParamsArray());
        $this->assertTrue($config->isConfigured());
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalConfig::toArray
     */
    public function testToArray()
    {
        $config = new LocalConfig($this->getParamsArray());
        $this->assertEquals($config->toArray(), $this->getParamsArray());
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalConfig::setRootDir
     */
    public function testSetRootDir()
    {
        $params = $this->getParamsArray();
        $validDir = $params["rootDir"];
        $invalidDir = "sajdlksajlks";

        $config = new LocalConfig($params);

        try {
            $config->setRootDir($invalidDir);
        } catch (FsClientConfigException $ex) {
            $this->assertTrue(true);
        }

        $config->setRootDir($validDir);
        $this->assertTrue(true);
    }

    /**
     * @covers \Titiyoyo\FsClient\Fs\Local\LocalConfig::setTmpDir
     */
    public function testSetTmpDir()
    {
        $params = $this->getParamsArray();
        $validDir = $params["tmpDir"];
        $invalidDir = "sajdlksajlks";

        $config = new LocalConfig($params);

        try {
            $config->setTmpDir($invalidDir);
        } catch (FsClientConfigException $ex) {
            $this->assertTrue(true);
        }

        $config->setTmpDir($validDir);
        $this->assertTrue(true);
    }

    private function getParamsArray()
    {
        return [
            "rootDir" => dirname(__FILE__),
            "tmpDir" => "/tmp",
            "defaultPermissions" => "2755"
        ];
    }
}
