<?php

namespace Tertere\FsClient\Fs\Local;

use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Tertere\FsClient\Fs\AbstractDirectory;
use Tertere\FsClient\Fs\DirectoryInterface;
use Tertere\FsClient\Fs\ItemInterface;

class LocalDirectory extends AbstractDirectory implements DirectoryInterface, ItemInterface
{
    use LocalTrait;

    private $oFs;

    public function __construct($path)
    {
        if ($this->validatePath($path)) {
            $this->setPath(realpath($path));
        }

        $this->list($this->path);

        $this->oFs = new Filesystem();
        $this->deleted = false;
    }

    public function getParent(): LocalDirectory
    {
        return $this->list(dirname($this->path));
    }

    public function isEmpty(): bool
    {
        return count($this->items) > 0 ? false : true;
    }

    public function validatePath($path): bool
    {
        return (bool)realpath($path);
    }

    public function list(): LocalDirectory
    {
        $files = scandir($this->path);

        foreach ($files as $item) {
            if (!in_array($item, $this->excludedFiles)) {
                $oItem = new LocalItem($this->path . "/" . $item);
                $this->items[] = $oItem;
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

    public function mkdir($name)
    {
        if (!is_writable($this->path)) {
            throw new IOException("path " . $this->path . " is not writeable", null, null, $this->path);
        }

        $this->oFs->mkdir($this->path . "/" . $name);
    }

    public static function create($path)
    {

    }

    public function rename($newName)
    {
        if (!is_writable($this->path)) {
            throw new IOException("path " . $this->path . " is not writeable", null, null, $this->path);
        }

        $newName = dirname($this->path) . '/' . preg_replace('/\..?\//', '', $newName);
        $this->oFs->rename($this->path, $newName);
        $this->path = $newName;
    }

    public function delete(): bool
    {
        try {
            $this->oFs->remove($this->path);
            unset($this->items);
            unset($this->dirs);
            unset($this->files);
            unset($this->links);
            $this->deleted = true;

            return true;
        } catch (\Exception $ex) {
            return false;
        }
    }
}
