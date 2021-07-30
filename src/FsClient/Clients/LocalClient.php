<?php

namespace Titiyoyo\FsClient\Clients;

use Psr\Log\LoggerInterface;
use Titiyoyo\FsClient\Fs\DirectoryInterface;
use Titiyoyo\FsClient\Fs\ItemInterface;
use Titiyoyo\FsClient\Fs\Local\LocalConfig;
use Titiyoyo\FsClient\Fs\Local\LocalDirectory;
use Titiyoyo\FsClient\Fs\Local\LocalItem;
use Symfony\Component\Filesystem\Filesystem;

class LocalClient extends AbstractClient implements ClientInterface
{
    private Filesystem $fsObj;
    private DirectoryInterface $currentDir;

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
        if (!$this->isAllowed($path)) {
            throw new \Exception('Path ' . $path . ' is forbidden');
        }

        $abspath = $this->getAbsPath($path);

        $this->currentDir = new LocalDirectory($abspath);
        return $this->currentDir;
    }

    public function isAllowed($path): bool
    {
        $rootDirPath = realpath($this->getConfig()->getRootDir());
        $requestedPath = realpath($this->getConfig()->getRootDir() . '/' . $path);

        return substr($requestedPath, 0, strlen($rootDirPath)) === $rootDirPath;
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
        return $this->currentDir->delete();
    }

    public function mkdir($name)
    {
        $this->currentDir->mkdir($name);
    }

    public function get($path): ItemInterface
    {
        if (!$this->fsObj->exists($this->getRootDir() . "/" . $path))
            throw new \Exception(__METHOD__ . " - file " . $path . " does not exist", 1);

        return new LocalItem($this->getRootDir() . "/" . $path);
    }

    public function toArray(): array
    {
        return $this->currentDir->toArray();
    }

    public function clearTmpFiles()
    {
        $dirs = array_diff(scandir($this->getConfig()->getTmpDir()), ['..', '.']);
        foreach ($dirs as $dir) {
            $this->fsObj->remove(
                $this->getConfig()->getTmpDir() . '/' . $dir
            );
        }
    }
}
