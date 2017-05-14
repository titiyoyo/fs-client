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

abstract class AbstractDirectory
{
    protected $items = [];
    protected $path;

    public function get($idx)
    {
        return $this->items[$idx];
    }

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


    abstract public function validatePath($path): bool;
}
