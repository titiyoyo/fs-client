<?php

namespace Tertere\FsClient\Fs\Local;

use PHPUnit\Runner\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Tertere\FsClient\Fs\AbstractDirectory;
use Tertere\FsClient\Fs\Local\LocalItem;

class LocalDirectory extends AbstractDirectory
{
    private $excludedFiles = [
        ".", "..", ".DS_Store"
    ];

    private $oFs;
    private $deleted;
    private $dirs = [];
    private $files = [];
    private $links = [];

    public function __construct($path)
    {
        if ($this->validatePath($path)) {
            $this->setPath(realpath($path));
        }

        $this->scanDir($this->path);

        $this->oFs = new Filesystem();
        $this->deleted = false;
    }

    public function get()
    {
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getDirs(): array
    {
        return $this->dirs;
    }

    public function getLinks(): array
    {
        return $this->links;
    }

    public function isEmpty(): bool
    {
        return (bool)count($this->items);
    }

    public function getExcludedFiles(): array
    {
        return $this->excludedFiles;
    }

    public function setExcludedFiles(array $excludedFiles)
    {
        return $this->excludedFiles;
    }

    public function validatePath($path): bool
    {
        return (bool)realpath($path);
    }

    private function scanDir($path): LocalDirectory
    {
        $files = scandir($path);

        foreach ($files as $item) {
            if (!in_array($item, $this->excludedFiles)) {
                $oItem = new LocalItem($path . "/" . $item);
                $this->items[] = $item;
                if ($oItem->isDir()) {
                    $this->dirs[] = $oItem;
                }
                if ($oItem->isFile()) {
                    $this->files[] = $oItem;
                }
                if ($oItem->isLink()) {
                    $this->links[] = $oItem;
                }
            }
        }

        return $this;
    }

    public function createSubDir()
    {
    }

    public function delete(): bool
    {
        if ($this->oFs->remove($this->path)) {
            unset($this->items);
            unset($this->dirs);
            unset($this->files);
            unset($this->links);
            $this->deleted = true;

            return true;
        }

        return false;
    }

    public function rename($newname)
    {
        $renamedDir = dirname($this->path) . "/" . basename($newname);
        $this->oFs->rename($this->path, $renamedDir);
        $this->path = realpath($renamedDir);
        return $this;
    }
}
