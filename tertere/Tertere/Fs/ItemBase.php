<?php

namespace Tertere\Fs;

abstract class ItemBase {
    private $video_formats 	    = ["AVI", "MOV", "MPG", "MPA", "ASF", "WMA", "MP2", "M2P", "RARE", "DIF", "MP4", "VOB"];
    private $audio_formats 	    = ["MP3", "AIFF", "AIF", "WAV", "PCM", "M4A"];

    public 
    	$isDir,
    	$filename,
    	$extension,
        $date,
        $size,
		$relativePath, 
		$absolutePath, 
		$hash, 
		$parentHash;

    abstract public function isDir();
    abstract public function toJson();

    public function getType() 
    {
        $type = "other";
        if (in_array(strtoupper($this->extension), $this->video_formats)) {
            $type = "video";
        } elseif (in_array(strtoupper($this->extension), $this->audio_formats)) {
            $type = "audio";
        }
        return $type;
    }

    private function getAbsolutePath($path)
    {
        $result                 = "";
        $pathChunks             = explode("/", $path);

        foreach($pathChunks as $chunk) {
            if($chunk != "" && !is_null($chunk) && $chunk != ".." && $chunk != ".") {
                $result        .= $chunk . "/";
            }
        }

        return $result;
    }

    protected function formatSize($str)
    {
        $str = trim($str);

        if($str > 1000000) {
            return round(($str / 1000000), 1) . " Mo";
        } else if($str > 1000) {
            return round($str / 1000, 1) . " Ko";
        } else {
            return "{$str} octets";
        }
    }
}

?>