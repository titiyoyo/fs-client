<?php

namespace Tertere\FsClient\Fs;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Tertere\FsClient\Exception\FsClientConfigException;

abstract class AbstractDirectory extends AbstractItem
{
    protected array $items = [];
    protected string $path;
    protected bool $deleted;
    protected array $dirs = [];
    protected array $files = [];
    protected array $links = [];

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

    public function getPath(): string
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

    public function toArray(
        \Closure $closure = null
    ){
        $items = [];
        foreach ($this->items as $item) {
            $items[] = $item->toArray($closure);
        }

        $dirs = [];
        foreach ($this->dirs as $dir) {
            $dirs[] = $dir->toArray($closure);
        }

        $files = [];
        foreach ($this->files as $file) {
            $files[] = $file->toArray($closure);
        }

        $links = [];
        foreach ($this->links as $link) {
            $links[] = $link->toArray($closure);
        }

        return [
            "path" => $this->path,
            "links" => $links,
            "files" => $files,
            "dirs" => $dirs,
            "items" => $items,
        ];
    }
}
