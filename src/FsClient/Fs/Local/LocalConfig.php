<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 02/04/2017
 * Time: 17:32
 */

namespace Tertere\FsClient\Fs\Local;

use Tertere\FsClient\Exception\FsClientConfigException;
use Tertere\FsClient\Fs\AbstractConfig;
use Tertere\FsClient\Fs\ConfigInterface;

class LocalConfig extends AbstractConfig implements ConfigInterface
{
    protected $defaultPermissions = 2755;

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

    private function validateDir($path)
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

    /**
     * @return null
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * @param null $rootDir
     */
    public function setRootDir($rootDir)
    {
        if (!$this->validateDir($rootDir)) {
            throw new FsClientConfigException(__METHOD__ . " - root dir is invalid");
        }

        $this->rootDir = $rootDir;
        return $this;
    }

    /**
     * @return null
     */
    public function getTmpDir()
    {
        return $this->tmpDir;
    }

    /**
     * @param null $tmpDir
     */
    public function setTmpDir($tmpDir)
    {
        if (!$this->validateDir($tmpDir)) {
            throw new FsClientConfigException(__METHOD__ . " - temp dir is invalid");
        }

        $this->tmpDir = $tmpDir;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getDefaultPermissions()
    {
        return $this->defaultPermissions;
    }

    /**
     * @param int|null $defaultPermissions
     */
    public function setDefaultPermissions($defaultPermissions)
    {
        $this->defaultPermissions = $defaultPermissions;
        return $this;
    }
}
