<?php

namespace Titiyoyo\FsClient\Fs;

abstract class AbstractItem
{
    protected ?string $mimeType;
    protected string $dirname;
    protected string $filename;
    protected ?string $size;
    protected ?string $sizeFormated;
    protected string $path;
    protected ?string $extension;
    protected string $type;
    protected string $uid;
    protected ?string $creationDate;
    protected ?string $modificationDate;

    protected bool $isDir;
    protected bool $isFile;
    protected bool $isLink;

    protected int $sizeInt;

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function toArray(\Closure $closure = null) {
        $array = [
            "path" => $this->path,
            "filename" => $this->filename,
            "sizeInt" => $this->size,
            "sizeFormated" => $this->sizeFormated,
            "isDir" => $this->isDir,
            "isFile" => $this->isFile,
            "isLink" => $this->isLink,
            "extension" => $this->extension,
            "creationDate" => $this->creationDate,
            "modificationDate" => $this->modificationDate,
            "mimeType" => $this->mimeType,
            "uid" => $this->uid,
        ];

        if ($closure) {
            $array = $closure($array);
        }

        return $array;
    }

    public function isDir()
    {
        return $this->isDir;
    }

    public function isLink()
    {
        return $this->isLink;
    }

    public function isFile()
    {
        return $this->isFile;
    }

    public function getPath()
    {
        return $this->path;
    }

    protected function formatSize($str)
    {
        $str = trim($str);

        if ($str > 1000000000) {
            return round(($str / 1000000000), 1) . " Go";
        } else if ($str > 1000000) {
            return round(($str / 1000000), 1) . " Mo";
        } elseif ($str > 1000) {
            return round($str / 1000, 1) . " Ko";
        } else {
            return "{$str} octets";
        }
    }

    public function getMimeType()
    {
        return $this->mimeType;
    }

    public function getDirname()
    {
        return $this->dirname;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function getSizeInt()
    {
        return $this->sizeInt;
    }

    public function getSizeFormated()
    {
        return $this->sizeFormated;
    }

    public function getExtension()
    {
        return $this->extension;
    }

    public function getCreationDate()
    {
        return $this->creationDate;
    }

    public function getModificationDate()
    {
        return $this->modificationDate;
    }
}
