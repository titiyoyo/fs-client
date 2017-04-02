<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 02/04/2017
 * Time: 16:48
 */

namespace Tertere\FsClient;

abstract class AbstractConfig
{
    private $rootDir;
    private $tmpDir;
    private $settingsArray;

    public function __construct($settingsArray)
    {
        $this->rootDir = $settingsArray["rootDir"] ?? null;
        $this->tmpDir = $settingsArray["tmpDir"] ?? null;
        $this->settingsArray = $settingsArray;
    }

    abstract function validate();

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
        $this->rootDir = $rootDir;
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
        $this->tmpDir = $tmpDir;
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