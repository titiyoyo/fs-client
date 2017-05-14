<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 02/04/2017
 * Time: 17:32
 */

namespace Tertere\FsClient\Fs;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Tertere\FsClient\Exception\FsClientConfigException;

abstract class AbstractDirectory extends AbstractItem
{
    protected $items = [];
    protected $path;
    protected $deleted;
    protected $dirs = [];
    protected $files = [];
    protected $links = [];

    protected $excludedFiles = [
        ".", "..", ".DS_Store"
    ];

    public function get($idx)
    {
        return $this->items[$idx];
    }

    abstract public function list();

    public function getByName($filename)
    {
        foreach ($this->items as $item) {
            if ($filename == $item->getFilename())
                return $item;
        }

        throw new FileNotFoundException("Couldn't find file " . $filename . ", can't rename");
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        if ($this->validatePath($path)) {
            $this->path = $path;
            return $this;
        }

        throw new FsClientConfigException(__METHOD__ . " - invalid path " . $path);
    }

    public function getItems(): array
    {
        return $this->items;
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

    public function getExcludedFiles(): array
    {
        return $this->excludedFiles;
    }

    public function setExcludedFiles(array $excludedFiles)
    {
        return $this->excludedFiles;
    }
}
