<?php

namespace Tertere\Fs\Local;

use PHPUnit\Runner\Exception;
use Symfony\Component\Filesystem\Filesystem;
use Tertere\FsClient\Fs\AbstractDirectory;
use \Tertere\FsClient\Fs\Local\LocalItem;

class LocalDirectory extends AbstractDirectory {

    private $excludedFiles = [
        ".", "..", ".DS_Store"
    ];

    private $oFs;
    private $path;
    private $deleted;
    private $items = [];
    private $dirs = [];
    private $files = [];
    private $links = [];

    public function __construct($path)
    {
        if (!realpath($path))
	        throw new Exception(__METHOD__ . " - path $path does not exist");

        $this->path = $path;
        $this->scanDir($this->path);

        $this->oFs = new Filesystem();
        $this->deleted = false;
	}

    public function getFiles() {
        return $this->files;
    }

    public function getDirs() {
        return $this->dirs;
    }

    public function getLinks() {
        return $this->links;
    }

    public function isEmpty() {
        return (bool)count($this->items);
    }

	private function scanDir($path) {
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
    }

    public function delete() {
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

    public function rename($newname) {
        return $this->oFs->rename($this->path, dirname($this->path) . "/" . $newname);
	}
}

?>