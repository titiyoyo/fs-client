<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 02/04/2017
 * Time: 17:42
 */

namespace Tertere\FsClient\Clients;

use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Exception\IOException;
use Tertere\FsClient\Fs\DirectoryInterface;
use Tertere\FsClient\Fs\ItemInterface;
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

    /**
     * @var LocalDirectory
     */
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

    public function browse($path): DirectoryInterface
    {
        $abspath = $this->getAbsPath($path);
        $this->currentDir = new LocalDirectory($abspath);
        return $this->currentDir;
    }

    public function getAbsPath($path)
    {
        $absPath = null;

        $paths = [
            $this->getConfig()->getRootDir() . $path,
            $this->getConfig()->getRootDir() . "/" . $path
        ];

        foreach ($paths as $curPath) {
            $tmpPath = realpath($curPath);
            if ($tmpPath) {
                $absPath = $tmpPath;
            }
        }

        if (!empty($absPath)) {
            return $absPath;
        }

        throw new \Exception(__METHOD__  . " - path $absPath is invalid");
    }

    public function getRelativePath($file, $path): string
    {
        return $file->getRelativePath($path);
    }

    public function delete()
    {
        try {
            return $this->currentDir->delete();
        } catch (IOExceptionInterface $e) {
            $this->logger->error(__METHOD__ . " - " . $e->getMessage() . " on path " . $this->currentDir->getPath() . " at line " . $e->getLine() . " in file " . $e->getFile());
            throw $e;
        }
    }

    public function mkdir($name)
    {
        try {
            $this->currentDir->mkdir($name);
        } catch (IOExceptionInterface $e) {
            $this->logger->error(__METHOD__ . " - " . $e->getMessage() . " on path " . $e->getPath() . " at line " . $e->getLine() . " in file " . $e->getFile());
            throw $e;
        }
    }

    public function get($path): ItemInterface
    {
        if (!$this->fsObj->exists($this->getRootDir() . "/" . $path))
            throw new \Exception(__METHOD__ . " - File " . $path . " does not exist on line " . __LINE__ . " in file " . __FILE__, 1);

        return new LocalItem($this->getRootDir() . "/" . $path);
    }

    public function rename($newName)
    {
        try {
            return $this->currentDir->rename($newName);
        } catch (IOExceptionInterface $e) {
            $this->logger->error(__METHOD__ . " - " . $e->getMessage() . " on path " . $e->getPath() . " at line " . $e->getLine() . " in file " . $e->getFile());
            throw $e;
        }
    }

    public function toArray()
    {
        return $this->currentDir->toArray();
    }

}
