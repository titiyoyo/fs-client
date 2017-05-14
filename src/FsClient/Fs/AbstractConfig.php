<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 02/04/2017
 * Time: 16:48
 */

namespace Tertere\FsClient\Fs;

abstract class AbstractConfig
{
    protected $rootDir;
    protected $tmpDir;
    protected $settingsArray;

    final public function __construct($settingsArray)
    {
        $this->validateConfiguration($settingsArray);
        $this->rootDir = $settingsArray["rootDir"];
        $this->tmpDir = $settingsArray["tmpDir"];
        $this->defaultPermissions = $settingsArray["defaultPermissions"] ?? null;
        $this->settingsArray = $settingsArray;
    }

    abstract public function validateConfiguration($settingsArray);

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
            throw new FsClientConfigException(__METHOD__ . " - tmp dir is invalid");
        }

        $this->tmpDir = $tmpDir;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getSettingsArray()
    {
        return $this->settingsArray;
    }

    /**
     * @param mixed $settingsArray
     */
    public function setSettingsArray($settingsArray)
    {
        $this->settingsArray = $settingsArray;
    }
}
