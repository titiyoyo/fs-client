<?php

namespace Tertere\FsClient\Fs\Local;

use Symfony\Component\Filesystem\Filesystem;
use Tertere\FsClient\Exception\FsClientConfigException;
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
        if (!realpath($path)) {
            throw new FsClientConfigException(__METHOD__ . " - path " . $path . " is invalid");
        }

        $this->path = $path;
        $this->pathinfo = pathinfo($this->path);
        $this->type = $this->getType();

        $this->filename = $this->pathinfo["basename"];
        $this->dirname = $this->pathinfo["dirname"];
        $this->extension = $this->pathinfo["extension"] ?? null;
        $this->size = filesize($this->path);
        $this->isDir = is_dir($this->path);
        $this->isLink = is_link($this->path);
        $this->isFile = is_file($this->path);
        $this->mimeType = mime_content_type($this->path);
        $this->creationDate = filectime($this->path);
        $this->modificationDate = filemtime($this->path);

        $this->oFs = new Filesystem();
    }

    public function getRelativePathTo($path)
    {
        if ($path === $this->path) {
            return ".";
        }

        if (preg_match("/^(\.|\.\.)/", $path)) {
            return $path;
        }

        if (!preg_match("/^\//", $path)) {
            return $path;
        }

        $pathToTest = $path;
        if (file_exists($path)) {
            $pathToTest = realpath($path);
            if (is_file($path)) {
                dirname($pathToTest);
            }
        }

        $localPath = $this->path;
        if ($this->isFile()) {
            $localPath = dirname($this->path);
        }

        // si le chemin n'existe pas et qu'il est absolu
        $testPathChunks = explode("/", $pathToTest);
        $itemPathChunks = explode("/", $localPath);

        $continue = true;
        $highestCommonDirIdx = null;
        $idx = 0;

        while ($continue) {
            $continue = false;
            if (isset($testPathChunks[$idx])
                && isset($itemPathChunks[$idx])
                && $testPathChunks[$idx] === $itemPathChunks[$idx]
            ) {
                $highestCommonDirIdx = $idx;
                $continue = true;
            }

            $idx++;
        }

        // si le chemin absolu un sous-repertoire de l'item courant
        if ($highestCommonDirIdx === (count($itemPathChunks) -1) && $this->isDir()) {
            return implode("/", array_slice($testPathChunks, $highestCommonDirIdx+1, count($testPathChunks)));
        }

        // on a le plus haut repertoire commun entre les deux repertoires
        // il faut maintenant compter combien de sous repertoires separent
        // le repertoire qu'on teste et le chemin de notre item

        $distance = count($itemPathChunks) -1 - ($highestCommonDirIdx + 1);
        return substr(str_repeat("../", $distance), 0, -1);
    }

    public function rename($newName)
    {
        $renamePath = dirname($this->path) . "/" . basename($newName);
        $this->oFs->rename($this->path, $renamePath);
    }

    public function delete()
    {
        $this->oFs->remove($this->path);
    }

    public function toArray()
    {
        $array = [
            "path" => $this->path,
            "filename" => $this->filename,
            "sizeInt" => $this->size,
            "sizeFormated" => $this->formatSize($this->size),
            "isDir" => $this->isDir,
            "extension" => $this->extension,
            "creationDate" => $this->creationDate,
            "modificationDate" => $this->modificationDate,
            "type" => $this->type
        ];

        return $array;
    }
}