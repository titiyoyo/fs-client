<?php

namespace Tertere\Fs\Local;
use \Tertere\Fs\Local\LocalItem as LocalItem;
use \Tertere\Fs\FsClientBase as FsClientBase;

class LocalClient extends FsClientBase {

	public function __construct($settingsArray, $path = null) {
		parent::__construct($settingsArray);
		$this->currentPath = $this->getAbsolutePath($this->homeDir . "/" . $path);
	}

	public function hasPermission($path) {
		$status = true;
		$requestedPathChunks = explode("/", $path);
		$userDirPathChunks = explode("/", $this->getAbsolutePath($this->homeDir));

		foreach ($userDirPathChunks as $key => $value) {
			$status = $status && ($value == $requestedPathChunks[$key]);
		}

		return $status;
	}

	public function isConfigured() {

	}

	public function getAbsolutePath($path) {
		return (!realpath($path) ? realpath($this->rootDir . "/" . $path) : $path);
	}

	public function getRelativePath($absPath) {
		return str_replace($this->getAbsolutePath($this->getHomeDir(), "", $absPath));
	}

	public static function test() {

	}

	public function delete($path) {
		if ($this->hasPermission($path)) {
			return unlink($this->homeDir . "/" . $path);
		}
	}

	public function getFile($path, $isRelative = true) {
		$absPath = $isRelative ? $this->getAbsolutePath($path) : $path;
		$relPath = $isRelative ? $path : $this->getRelativePath($path);
		if (file_exists($absPath) && is_file($absPath)) {
			return new LocalItem($absPath, $relPath);
		}
		
		throw new \Exception(__METHOD__ . " - File " . $path . " does not exist on line " . __LINE__ . " in file " . __FILE__, 1);
	}

	public function fileExists($path, $relative = true) {
		$path = ($relative == true ? $this->getAbsolutePath($path) : realpath($path));
		return $path && file_exists($path);
	}

	public function rename($path, $newPath, $relative = true) {
		if ($relative) {
			$path = $this->getAbsolutePath($path);
			$newPath = $this->getRootDir() . "/" . $this->getHomeDir() . "/" . $newPath;
		}
		if (!$this->fileExists($path, !$relative)) {
			throw new \Exception(__METHOD__ . " - file $path does not exist at line " . __LINE__ . " in file " . __FILE__, 1);			
		}
		if (!($this->hasPermission($path) && $this->hasPermission($newPath))) {
			throw new \Exception(__METHOD__ . " - user does not have permission, can't rename $path to $newPath" . __LINE__ . " in file " . __FILE__, 1);
		}
		
		return rename($path, $newPath);
	}

	public function move($filePath, $targetPath) {
		
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

	public function chunkExists($tmpSubDir, $filename, $chunkNumber) {
		$chunk_file = $this->tmpDir . '/' . $tmpSubDir . "/" . $filename . '.part' . $chunkNumber;
		return (bool)realpath($chunk_file);
	}

	public function createFileFromChunks($tmpDir, $filename, $totalSize, $totalChunks) {

	    // count all the parts of this file
	    $total_files_on_server_size = 0;
	    $temp_total = 0;
	    $chunkFiles = scandir($tmpDir);
	    if (count($chunkFiles) - 2 == $totalChunks) {
		    foreach(scandir($tmpDir) as $file) {
		        $temp_total = $total_files_on_server_size;
		        $tempfilesize = filesize($tmpDir . '/' . $file);
		        $total_files_on_server_size = $temp_total + $tempfilesize;
		    }

		    // check that all the parts are present
		    // If the Size of all the chunks on the server is equal to the size of the file uploaded.
		    if ($total_files_on_server_size >= $totalSize) {
		        if (($fp = fopen($tmpDir . "/" . $filename, 'w')) !== false) {
		            for ($i=1; $i<=$totalChunks; $i++) {
		                fwrite($fp, file_get_contents($tmpDir . '/' . $filename . '.part-' . $i));
		            }
		            fclose($fp);
		        } else {
		            $this->logger->error( __METHOD__ . ' - cannot create the destination file ' . $filename . " on " . __LINE__ . " in file " . __FILE__);
		            return false;
		        }
		    }
	    }

	}

	public function createFile($path) {
		if (!file_exists($this->getAbsolutePath($this->homeDir)  . "/" . $path))
			return mkdir($this->getAbsolutePath($this->homeDir)  . "/" . $path);
	}

	public function createDir($path) {
		if (!file_exists($this->getAbsolutePath($this->homeDir)  . "/" . $path))
			return mkdir($this->getAbsolutePath($this->homeDir)  . "/" . $path);
	}

	public function list($path = ".", $parentHash = "") {
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