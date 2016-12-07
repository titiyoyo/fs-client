<?php
namespace Tertere\Fs\Local;
use \Tertere\Fs\ItemBase as ItemBase;

/**
 * Created by JetBrains PhpStorm.
 * User: terencepires
 * Date: 17/02/12
 * Time: 07:12
 * To change this template use File | Settings | File Templates.
 */
class LocalItem extends ItemBase
{
    public $mimeType = null;

    public function __construct($absolutePath, $relativePath) {
    	$this->absolutePath = $absolutePath;
    	$this->relativePath = $relativePath;
    	$this->filename = basename($absolutePath);
    	$this->size = filesize($this->absolutePath);
    	$this->isDir = is_dir($this->absolutePath);
        $this->extension = pathinfo($this->absolutePath, PATHINFO_EXTENSION);
    	$this->mimeType = mime_content_type($this->absolutePath);
    	$this->date = filectime($this->absolutePath);
    	$this->type = $this->getType();
    }

    public function isDir() {
    	return $this->isDir;
    }

    public function toArray($toHide = array()) {
    	$array = [
    		"absolutePath" => $this->absolutePath,
    		"relativePath" => $this->relativePath,
    		"filename" => $this->filename,
            "sizeInt" => $this->size,
    		"sizeFormated" => $this->formatSize($this->size),
    		"isDir" => $this->isDir,
    		"extension" => $this->extension,
    		"date" => $this->date,
    		"type" => $this->type
    	];

    	foreach ($array as $key => &$value) {
    		if (in_array($key, $toHide)) {
    			unset($array[$key]);
    		}
    	}

    	return $array;
    }

    public function read() {
        return readfile($this->absolutePath);
    }

    public function toJson() {
        return json_encode($this->toArray());
    }
}