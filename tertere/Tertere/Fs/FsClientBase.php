<?php

namespace Tertere\Fs;

abstract class FsClientBase {
	const MODE_LOCAL = 1;
	const MODE_FTP = 2;

	protected
		$config,
		$rootDir,
		$tmpDir,
		$homeDir,
		$currentPath;

	abstract public function hasPermission($path);
	abstract public function isConfigured();
	abstract public function delete($path);
	abstract public function rename($path, $newName);
	abstract public function put($localPath, $targetPath);
	abstract public function createFile($path);
	abstract public function createDir($path);
	abstract public function list($path, $parentHash = "");

	abstract public static function test();

	public function __construct($settingsArray) {
		$this->config = $settingsArray;
		$this->rootDir = $settingsArray["root_dir"];
		$this->tmpDir = $settingsArray["tmp_dir"];
		$this->homeDir = $settingsArray["home_dir"];
	}

	public function getBreadcumbArray($path) {
		$absolutePath = realpath($this->rootDir);
		$homeDirAbsolutePath = realpath($absolutePath . "/" . $this->homeDir);
		$relativeHome = str_replace($absolutePath, "", $homeDirAbsolutePath);

		return 
			explode("/",
				preg_replace(
					"#^/?/#", 
					"",
					str_replace($this->homeDir, "", $relativeHome . "/" . $path)
				)
			);
	}

	public function getAbsolutePath($path)
	{
		$result = "";
		$pathChunks = explode("/", $path);

		foreach($pathChunks as $chunk)
		{
			if($chunk != "" && !is_null($chunk) && $chunk != ".." && $chunk != ".")
			{
				$result .= $chunk . "/";
			}
		}

		return $result;
	}

	public function makeZip($pathArray, $outputPath)
	{
		$date = date("ymdHis");
		$zipRemoteFileName = "files{$date}.zip";
		$zipCommand = "zip -j0 {$this->tmpDir}/{$zipRemoteFileName} ";
		$path = $this->getAbsolutePath($path);
		$filesCount = count($filesArray);

		if($filesCount > 1) {
			foreach($filesArray as $file) {
				$zipCommand .= "'{$this->rootDir}/{$this->homeDir}/{$path}/{$file}' ";
				$zipCommand = preg_replace("/\/$/", "", $zipCommand);
			}

			$zipCommand = str_replace("//", "/", $zipCommand);
			exec($zipCommand, $array);

			return $this->tmpDir . "/" . $zipRemoteFileName;
		} elseif($filesCount == 1) {
			$path = "{$this->rootDir}/{$this->homeDir}/{$path}/{$filesArray[0]}";
			return $path;
		}
	}

	// Getters/setters
	public function getRootDir()
	{
		return $this->rootDir;
	}

	public function setRootDir($dir)
	{
		$this->rootDir = $dir;
	}

	public function getTmpDir()
	{
		return $this->tmpDir;
	}

	public function setTmpDir($dir)
	{
		$this->tmpDir = $dir;
	}

	public function getHomeDir()
	{
		return $this->homeDir;
	}

	public function setHomeDir($dir)
	{
		$this->homeDir = $dir;
	}

	public function getCurrentPath()
	{
		return $this->currentPath;
	}
}

?>