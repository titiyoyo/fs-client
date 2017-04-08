<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 02/04/2017
 * Time: 17:42
 */

namespace Tertere\FsClient\Clients;

use Tertere\FsClient\Fs\Local\LocalConfig;
use Tertere\FsClient\Fs\Local\LocalDirectory;
use Tertere\FsClient\User\FsUser;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterfaceuse;

class LocalClient extends AbstractClient
{
    private $fsObj;
    private $currentDir;

    public function __construct($configSettings, LoggerInterface $logger)
    {
        $config = new LocalConfig($configSettings);
        $user = new FsUser($config->getHomeDir(), [UserRight::ADMIN, UserRight::USER]);
        $this->fsObj = new Filesystem();
        parent::__construct($user, $config, $logger);
    }

    public function isConfigured()
    {
        return $this->config->isConfigured();
    }

    public function browse($path): LocalDirectory
    {
        $this->currentDir = $this->setCurrentDir($path);
        return new LocalDirectory($path);
    }

    public function setCurrentDir($path)
    {
        $absPath = null;
        if ($this->fsObj->isAbsolutePath($path)) {
            $absPath = $path;
        } elseif ($this->fsObj->exists($this->config->getRootDir() . "/" . $path)) {
            $absPath = $this->config->getRootDir() . "/" . $path;
        }

        if (realpath($absPath)) {
            return $absPath;
        }

        throw new \Exception(__METHOD__  . " - path $path is invalid");
    }

    public function getRelativePath($file, $path)
    {
        return $file->getRelativePath($path);
    }

    public function mkdir($path)
    {
        try {
            if (!$this->fsObj->exists($path) && $this->hasPermission($path)) {
                $this->fsObj->mkdir($path);
            }
        } catch (IOExceptionInterface $e) {
            echo "An error occurred while creating your directory at ".$e->getPath();
        }
    }


    public function delete($path)
    {
        try {
            if ($this->hasPermission($path)) {
                return unlink($this->user->getHomeDir() . "/" . $path);
            }
        } catch (IOExceptionInterface $e) {
            "An error occurred while creating your directory at ".$e->getPath();
        }
    }

    public function get($path)
    {
        throw new \Exception(__METHOD__ . " - File " . $path . " does not exist on line " . __LINE__ . " in file " . __FILE__, 1);
    }

    public function getRootDir()
    {
        return $this->config->getRootDir();
    }

    public function getTmpDir()
    {
        return $this->config->getTmpDir();
    }

    public function getHomeDir()
    {
        return $this->user->getHomeDir();
    }
}
