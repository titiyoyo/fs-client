<?php

namespace Tertere\FsClient\Fs\Local;

use Tertere\FsClient\Utilities\MimeTypes;

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
        $this->size = $this->filesize($this->path);
        $this->sizeFormated = $this->formatSize($this->size);
        $this->isDir = $this->is_dir($this->path);
        $this->isLink = $this->is_link($this->path);
        $this->isFile = $this->is_file($this->path);
        $this->mimeType = $this->mimeType($this->path);
        $this->creationDate = $this->filectime($this->path);
        $this->modificationDate = $this->filemtime($this->path);
        $this->uid = uniqid();
    }

    public function is_dir($path)
    {
        $isDir = is_dir($path);
        if (!$isDir) {
            $isDir = $this->checkFileType($path, 'd');
        }

        return $isDir;
    }

    public function is_link($path)
    {
        $isLink = is_link($path);
        if (!$isLink) {
            $isLink = $this->checkFileType($path, 'L');
        }

        return $isLink;
    }

    public function is_file($path)
    {
        $isFile = is_file($path);
        if (!$isFile) {
            $isFile = $this->checkFileType($path, 'f');
        }

        return $isFile;
    }

    private function checkFileType($path, $type)
    {
        $output = trim(shell_exec('if [ -$type "' . $path . '" ]; then echo true; else echo false; fi'));
        return $output === 'true' ? true : false;
    }

    public function mimeType($path)
    {
        $mime = null;

        try {
            $mime = mime_content_type($path);
            if (empty($mtime)) {
                $mime = trim(shell_exec('file -b --mime-type "' . $path . '"'));
            }
            if ('application/octet-stream' === $mime) {
                $mime = MimeTypes::extensionToMimetype($this->getExtension());
            }
        } catch (\Throwable $ex) {
            $mime = trim(shell_exec('file -b --mime-type "' . $path . '"'));
        }

        return $mime;
    }

    public function filectime($path)
    {
        $ctime = '';

        try {
            $ctime = filectime($path);
            if (empty($mtime)) {
                $ctime = $this->formatStatDate(shell_exec('stat -c %y "' . $path . '"'));
            }
        } catch (\Throwable $ex) {
            $ctime = $this->formatStatDate(shell_exec('stat -c %y "' . $path . '"'));
        }

        return trim($ctime);
    }

    public function filemtime($path)
    {
        $mtime = '';

        try {
            $mtime = filemtime($path);
            if (empty($mtime)) {
                $mtime = $this->formatStatDate(shell_exec('stat -c %y "' . $path . '"'));
            }

        } catch (\Throwable $ex) {
            $mtime = $this->formatStatDate(shell_exec('stat -c %y "' . $path . '"'));
        }

        return trim($mtime);
    }

    private function formatStatDate($date)
    {
        $date = preg_replace('/\..*/', '', trim($date));
        $dateTime = \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $date
        );

        return $dateTime->getTimestamp();
    }

    public function filesize($path)
    {
        $size = null;

        try {
            $size = filesize($path);
            if (empty($size)) {
                $size = shell_exec('wc -c < "' . $path . '"');
            }
        } catch (\Throwable $ex) {
            $size = shell_exec('wc -c < "' . $path . '"');
        }

        return trim($size);
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