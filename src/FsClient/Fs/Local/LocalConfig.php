<?php

namespace Titiyoyo\FsClient\Fs\Local;

use Titiyoyo\FsClient\Exception\FsClientConfigException;
use Titiyoyo\FsClient\Fs\AbstractConfig;
use Titiyoyo\FsClient\Fs\ConfigInterface;

class LocalConfig extends AbstractConfig implements ConfigInterface
{
    protected int $defaultPermissions = 2755;

    public function validateConfiguration($paramsArray)
    {
        if (!isset($paramsArray["rootDir"])) {
            throw new FsClientConfigException(__METHOD__ . " - no root dir set");
        }

        if (!$this->validateDir($paramsArray["rootDir"])) {
            throw new FsClientConfigException(__METHOD__ . " - root dir ${$paramsArray["rootDir"]} is invalid");
        }

        if (!isset($paramsArray["tmpDir"])) {
            throw new FsClientConfigException(__METHOD__ . " - no temp dir set");
        }

        if (!$this->validateDir($paramsArray["tmpDir"])) {
            throw new FsClientConfigException(__METHOD__ . " - temp dir is invalid");
        }
    }

    protected function validateDir($path)
    {
        return realpath($path);
    }

    public function isConfigured()
    {
        if (is_readable($this->tmpDir) && is_writable($this->tmpDir)) {
            return true;
        }

        throw new \Exception(__METHOD__ . " - Cannot read or write from root directory at line " . __LINE__ . " ");
    }

    public function toArray()
    {
        return [
            "rootDir" => $this->rootDir,
            "tmpDir" => $this->tmpDir,
            "defaultPermissions" => $this->defaultPermissions
        ];
    }

    public function getRootDir()
    {
        return $this->rootDir;
    }

    public function setRootDir($rootDir)
    {
        if (!$this->validateDir($rootDir)) {
            throw new FsClientConfigException(__METHOD__ . " - root dir is invalid");
        }

        $this->rootDir = $rootDir;
        return $this;
    }

    public function getTmpDir()
    {
        return $this->tmpDir;
    }

    public function setTmpDir($tmpDir)
    {
        if (!$this->validateDir($tmpDir)) {
            throw new FsClientConfigException(__METHOD__ . " - temp dir is invalid");
        }

        $this->tmpDir = $tmpDir;
        return $this;
    }

    public function getDefaultPermissions()
    {
        return $this->defaultPermissions;
    }

    public function setDefaultPermissions($defaultPermissions)
    {
        $this->defaultPermissions = $defaultPermissions;
        return $this;
    }
}
