<?php

namespace Tertere\Fs\Local;
use \Tertere\Fs\Local\LocalItem as LocalItem;
use \Tertere\Fs\FsClientBase as FsClientBase;

class LocalClient extends FsClientBase {



	public function hasPermission($path) {
		$status = true;
		$requestedPathChunks = explode("/", realpath($this->homeDir . "/" . $path));
		$userDirPathChunks = explode("/", realpath($this->homeDir));

		foreach ($userDirPathChunks as $key => $value) {
			$status = $status && ($value == $requestedPathChunks[$key]);
		}

		return $status;
	}

	public function isConfigured() {

	}

	public static function test() {

	}

	public function delete($path) {
		return unlink($this->getAbsolutePath($this->userDir . "/" . $path));
	}

	public function rename($path, $newName) {
		if ($this->hasPermission($path)) {
			$dir = dirname($path);
			$filename = basename($path);
			return rename($path, $dir . "/" . $newName);
		}
	}

	public function put($localPath, $targetPath) {

	}

	public function createFile($path) {
		return touch($this->getAbsolutePath($this->userDir . "/" . $path));
	}

	public function createDir($path) {
		return mkdir($this->getAbsolutePath($this->userDir . "/" . $path));
	}

	public function list($path = ".", $parentHash = "") {
		$ignore = [".", "..", ".DS_Store"];
		$relativePath = $path;
		$absolutePath = realpath($this->rootDir . "/" . $this->homeDir . "/" . $path);
		$this->currentPath = $absolutePath;

		if ($this->hasPermission($absolutePath)) {
			$filteredFilesList = [];
			$files = scandir($absolutePath);
			foreach ($files as $file) {
				if (!in_array($file, $ignore)) {
					$localItem = new LocalItem($absolutePath . "/" . $file, $relativePath . "/" . $file);
					$filteredFilesList[] = $localItem->toArray(["absolutePath"]);
				}
			}

			return $filteredFilesList;
		} else {
			throw new \Exception("User does not have permission to access path " . $path);
		}

	}
}

?>