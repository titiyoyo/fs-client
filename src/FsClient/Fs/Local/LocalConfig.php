<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 02/04/2017
 * Time: 17:32
 */

namespace Tertere\FsClient\Fs\Local;

class LocalConfig
{
    private $defaultPermissions = 755;

    public function __construct($paramsArray)
    {
        if (!isset($paramsArray["rootDir"]))
            throw new \Exception();

        $this->rootDir = $paramsArray["rootDir"];
        $this->tmpDir = $paramsArray["tmpDir"];
        $this->defaultPermissions = $paramsArray["defaultPermissions"] ?? null;
    }

    public function isConfigured() {
        if (is_readable($this->tmpDir) && is_writable($this->tmpDir)) {
            return true;
        }

        throw new \Exception(__METHOD__ . " - Cannot read or write from root directory at line " . __LINE__ . " ");
    }

    public function toArray() {
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
    }
}