<?php

namespace Tertere\FsClient\Fs;

abstract class AbstractItem
{
    private $video_formats = ["AVI", "MOV", "MPG", "MPA", "ASF", "WMA", "MP2", "M2P", "RARE", "DIF", "MP4", "VOB"];
    private $audio_formats = ["MP3", "AIFF", "AIF", "WAV", "PCM", "M4A"];
    private $photo_formats = ["JPG", "PNG", "TIFF", "BMP", "GIF", "ICO"];
    private $document_formats = ["PDF", "DOC", "RTF", "TXT", "NFO"];
    private $archive_formats = ["ZIP", "TAR", "TAR.GZ", "7Z", "RAR"];

    protected $mimeType;
    protected $dirname;
    protected $filename;
    protected $size;
    protected $sizeFormated;
    protected $isDir;
    protected $isFile;
    protected $isLink;
    protected $path;
    protected $extension;
    protected $creationDate;
    protected $modificationDate;
    protected $type;

    public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function toArray(
        \Closure $closure = null
    ) {
        $array = [
            "path" => $this->path,
            "filename" => $this->filename,
            "sizeInt" => $this->size,
            "sizeFormated" => $this->sizeFormated,
            "isDir" => $this->isDir,
            "extension" => $this->extension,
            "creationDate" => $this->creationDate,
            "modificationDate" => $this->modificationDate,
            "mimeType" => $this->mimeType,
            "type" => $this->type,
            "uid" => $this->uid,
        ];

        if ($closure) {
            $array = $closure($array);
        }

        return $array;
    }

    public function getType()
    {
        $type = "other";
        if (in_array(strtoupper($this->extension), $this->video_formats)) {
            $type = "video";
        } elseif (in_array(strtoupper($this->extension), $this->audio_formats)) {
            $type = "audio";
        } elseif (in_array(strtoupper($this->extension), $this->photo_formats)) {
            $type = "photo";
        } elseif (in_array(strtoupper($this->extension), $this->document_formats)) {
            $type = "document";
        } elseif (in_array(strtoupper($this->extension), $this->archive_formats)) {
            $type = "archive";
        } elseif ($this->isDir) {
            $type = "folder";
        } elseif ($this->isLink) {
            $type = "link";
        }
        return $type;
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

        if ($str > 1000000) {
            return round(($str / 1000000), 1) . " Mo";
        } elseif ($str > 1000) {
            return round($str / 1000, 1) . " Ko";
        } else {
            return "{$str} octets";
        }
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return mixed
     */
    public function getDirname()
    {
        return $this->dirname;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getSizeInt()
    {
        return $this->sizeInt;
    }

    /**
     * @return mixed
     */
    public function getSizeFormated()
    {
        return $this->sizeFormated;
    }



    /**
     * @return mixed
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return mixed
     */
    public function getModificationDate()
    {
        return $this->modificationDate;
    }
}
