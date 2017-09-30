<?php


namespace Tertere\FsClient\Fs\Local;


trait LocalTrait
{
    public function rename($newName)
    {
        $filename = basename($newName);
        $renamePath = dirname($this->path) . "/" . $filename;
        $this->oFs->rename($this->path, $renamePath);
        if (realpath($this->dirname . "/" . $filename)) {
            $this->path = $this->dirname . "/" . $filename;
            $this->setProperties();
        }
    }

    private function setProperties()
    {
        $this->pathinfo = pathinfo($this->path);

        $this->filename = $this->pathinfo["basename"];
        $this->dirname = $this->pathinfo["dirname"];
        $this->extension = $this->pathinfo["extension"] ?? null;
        $this->size = filesize($this->path);
        $this->sizeFormated = $this->formatSize($this->size);
        $this->isDir = is_dir($this->path);
        $this->isLink = is_link($this->path);
        $this->isFile = is_file($this->path);
        $this->mimeType = mime_content_type($this->path);
        $this->creationDate = filectime($this->path);
        $this->modificationDate = filemtime($this->path);
        $this->uid = uniqid();

        $this->type = $this->getType();
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
}