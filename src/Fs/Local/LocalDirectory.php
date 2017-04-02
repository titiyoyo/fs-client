<?php

namespace Tertere\Fs\Local;

use Tertere\Fs\AbstractDirectory;
use \Tertere\Fs\Local\LocalItem;

class LocalDirectory extends AbstractDirectory {

	public function __construct() {
	}

	/**
	 * 
	 * Delete a directory RECURSIVELY
	 * @param string $dir - directory path
	 * @link http://php.net/manual/en/function.rmdir.php
	 */
	public function rrmdir($dir) {
	    if (is_dir($dir)) {
	        $objects = scandir($dir);
	        foreach ($objects as $object) {
	            if ($object != "." && $object != "..") {
	                if (filetype($dir . "/" . $object) == "dir") {
	                    $this->rrmdir($dir . "/" . $object); 
	                } else {
	                    unlink($dir . "/" . $object);
	                }
	            }
	        }
	        reset($objects);
	        rmdir($dir);
	    }
	}

	public function list($path = ".") {
		$ignore = [".", "..", ".DS_Store"];
		$relativePath = $path;
		$absolutePath = realpath($this->rootDir . "/" . $this->homeDir . "/" . $path);
		$this->currentPath = $absolutePath;

		$dirItemsArray = [
			"status" => false,
			"itemsArray" => []
		];
		if (is_dir($absolutePath)) {
			$dirItemsArray["status"] = true;
			$files = scandir($absolutePath);
			foreach ($files as $file) {
				if (!in_array($file, $ignore)) {
					$localItem = new LocalItem($absolutePath . "/" . $file, $relativePath . "/" . $file);
					$dirItemsArray["itemsArray"][] = $localItem->toArray(["absolutePath"]);
				}
			}
		}

		return $dirItemsArray;

	}
}

?>