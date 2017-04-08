<?php

namespace Tertere\Fs\Local;

use Symfony\Component\Filesystem\Filesystem;
use Tertere\FsClient\Fs\AbstractItem;

/**
 * Created by JetBrains PhpStorm.
 * User: terencepires
 * Date: 17/02/12
 * Time: 07:12
 * To change this template use File | Settings | File Templates.
 */
class LocalItem extends AbstractItem
{
    protected $oFs;
    protected $pathinfo;

    public function __construct($path)
    {
        $this->path = $path;
        $this->pathinfo = pathinfo($this->path);
        $this->type = $this->getType();

        $this->filename = $this->pathinfo["basename"];
        $this->dirname = $this->pathinfo["dirname"];
        $this->extension = $this->pathinfo["extension"];
        $this->size = filesize($this->path);
        $this->isDir = is_dir($this->path);
        $this->isLink = is_link($this->path);
        $this->isFile = is_file($this->path);
        $this->mimeType = mime_content_type($this->absolutePath);
        $this->creationDate = filectime($this->absolutePath);
        $this->modificationDate = filemtime($this->path);

        $this->oFs = new Filesystem();
    }

    public function getRelativePath($path)
    {
        return $this->oFs->makePathRelative($this->path, $path);
    }

    public function rename($newName)
    {
        $this->oFs->rename($this->path, dirname($newName) . "/" . $newName);
    }

    public function delete()
    {
        $this->oFs->remove($this->path);
    }

    public function toArray()
    {
        $array = [
            "absolutePath" => $this->absolutePath,
            "filename" => $this->filename,
            "sizeInt" => $this->size,
            "sizeFormated" => $this->formatSize($this->size),
            "isDir" => $this->isDir,
            "extension" => $this->extension,
            "date" => $this->date,
            "type" => $this->type
        ];

        return $array;
    }
}
