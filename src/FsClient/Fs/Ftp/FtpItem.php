<?php

namespace Titiyoyo\FsClient\Fs\Ftp;

use \Tertere\FsClient\Fs\AbstractItem;
use Titiyoyo\FsClient\Fs\ItemInterface;

/**
 * Created by JetBrains PhpStorm.
 * User: terencepires
 * Date: 17/02/12
 * Time: 07:12
 * To change this template use File | Settings | File Templates.
 */
class FtpItem extends AbstractItem implements ItemInterface
{
    public $permissions;
    public $num;
    public $owner;
    public $group;
    public $size;
    public $date;

    public function __construct($ftpRawListString, $path, $parentHash = "")
    {
        $info                   = $this->parseFtpRawList($ftpRawListString);

        $this->permissions      = $info[0];
        $this->num              = $info[1];
        $this->owner            = $info[2];
        $this->group            = $info[3];
        $this->size             = $info[4];
        $this->date             = "{$info[5]} {$info[6]} {$info[7]}";
        $this->filename         = $info[8];

        $this->relativePath     = $path;
        $this->absolutePath     = $this->getAbsolutePath($path);
        $this->size             = $this->formatSize($this->size);
        $this->extension        = $this->getFileExtension($this->filename);
        $this->isDir            = $this->isDir($this->permissions);
        $this->type             = $this->getType();
    }

    public function getInfoArray()
    {
        return array(
            "absolutePath"      => $this->absolutePath,
            "permissions"       => $this->permissions,
            "num"               => $this->num,
            "owner"             => $this->owner,
            "group"             => $this->group,
            "size"              => $this->size,
            "date"              => $this->date,
            "filename"          => $this->filename,
            "extension"         => $this->extension,
            "isDir"             => $this->isDir,
        );
    }

    private function getFileExtension($file)
    {
        $explodedFilename   = explode(".", $file);
        if (count($explodedFilename) > 1) {
            return $explodedFilename[count($explodedFilename) -1];
        } else {
            return null;
        }
    }

    private function parseFtpRawList($ftpRawListString)
    {
        return preg_split("/[\s]+/", $ftpRawListString, 9, PREG_SPLIT_NO_EMPTY);
    }

    public function isDir()
    {
        if (substr($this->permissions, 0, 1) == "d") {
            return true;
        }

        return false;
    }

    public function toJson()
    {
        return json_encode($this->getInfoArray());
    }

    public function toArray(\Closure $closure = null)
    {
        return [];
    }

    public function rename($newFilename)
    {
        // TODO: Implement rename() method.
    }

    public function getRelativePathTo($path)
    {
        // TODO: Implement getRelativePathTo() method.
    }

    public function getParent()
    {
        // TODO: Implement getParent() method.
    }

    public function delete(): bool
    {
        // TODO: Implement delete() method.
    }
}
