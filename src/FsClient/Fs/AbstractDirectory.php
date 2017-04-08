<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 02/04/2017
 * Time: 17:32
 */

namespace Tertere\FsClient\Fs;

use Tertere\FsClient\Exception\FsClientConfigException;

abstract class AbstractDirectory
{
    protected $items = [];
    protected $path;

    public function get($idx)
    {
        return $this->items[$idx];
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
