<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 02/04/2017
 * Time: 17:42
 */

namespace Tertere\FsClient\Clients;

use Psr\Log\LoggerInterface;
use Tertere\FsClient\Fs\Local\LocalConfig;
use Tertere\FsClient\Fs\Local\LocalDirectory;
use Tertere\FsClient\Fs\Local\LocalItem;
use Tertere\FsClient\User\User;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class LocalClient extends AbstractClient implements ClientInterface
{
    const MODE = 1;

    private $fsObj;
    private $currentDir;

    public function __construct(LocalConfig $config, LoggerInterface $logger)
    {
        $this->fsObj = new Filesystem();
        parent::__construct($config, $logger);
    }

    public function isConfigured(): bool
    {
        return $this->config->isConfigured();
    }

    public function browse($path): LocalDirectory
    {
        $this->currentDir = $this->setCurrentDir($path);
        return new LocalDirectory($this->currentDir);
    }

    public function setCurrentDir($path)
    {
        $absPath = $path;
        if (!$this->fsObj->isAbsolutePath($path)) {
            $absPath = $this->config->getRootDir() . "/" . $path;
        }

        $absPath = realpath($absPath);

        if (!empty($absPath)) {
            return $absPath;
        }

        throw new \Exception(__METHOD__  . " - path $absPath is invalid");
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
        if (!$this->fsObj->exists($path))
            throw new \Exception(__METHOD__ . " - File " . $path . " does not exist on line " . __LINE__ . " in file " . __FILE__, 1);

        return new LocalItem($path);
    }

    public function getRootDir()
    {
        return $this->config->getRootDir();
    }

    public function getTmpDir()
    {
        return $this->config->getTmpDir();
    }
}
