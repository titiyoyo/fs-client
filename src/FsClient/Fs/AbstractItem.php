<?php

namespace Tertere\FsClient\Fs;

abstract class AbstractItem
{
    private $video_formats        = ["AVI", "MOV", "MPG", "MPA", "ASF", "WMA", "MP2", "M2P", "RARE", "DIF", "MP4", "VOB"];
    private $audio_formats      = ["MP3", "AIFF", "AIF", "WAV", "PCM", "M4A"];
    private $photo_formats        = ["JPG", "PNG", "TIFF", "BMP", "GIF"];

    protected $mimeType;
    protected $dirname;
    protected $filename;
    protected $size;
    protected $isDir;
    protected $isFile;
    protected $isLink;
    protected $path;
    protected $extension;
    protected $creationDate;
    protected $modificationDate;
    protected $type;

    abstract public function toArray();

    public function toJson()
    {
        return json_encode($this->toArray());
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

    public function getPath() {
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
}
