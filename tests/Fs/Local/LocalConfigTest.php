<?php

namespace Tertere\Test\Fs\Local;

use \PHPUnit\Framework\TestCase;
use Tertere\FsClient\Fs\Local\LocalConfig;
use Tertere\FsClient\Exception\FsClientConfigException;

class LocalConfigTest extends TestCase
{
    public function testConstruct()
    {
        try {
            new LocalConfig([]);
        } catch (FsClientConfigException $ex) {
            $this->assertTrue(true);
        }

        try {
            new LocalConfig(["rootDir" => "", "tmpDir"]);
        } catch (FsClientConfigException $ex) {
            $this->assertTrue(true);
        }
    }

    public function testIsConfigured()
    {
        $config = new LocalConfig($this->getParamsArray());
        $this->assertTrue($config->isConfigured());
    }

    public function testToArray()
    {
        $config = new LocalConfig($this->getParamsArray());
        $this->assertEquals($config->toArray(), $this->getParamsArray());
    }

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
