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
		$currentPath,
		$logger;

	abstract public function isConfigured();
	abstract public function delete($path);
	abstract public function rename($path, $newName);
	abstract public function move($localPath, $targetPath);
	abstract public function createDir($path);
	abstract public function list($path, $parentHash = "");
	abstract public function getAbsolutePath($path);

	abstract public static function test();

	public function __construct($settingsArray) {
		$this->logger = $settingsArray["logger"];
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

	public function makeZipSystem($pathArray)
	{
		$date = date("ymdHis");
		$zipRemoteFileName = "files{$date}.zip";
		$zipCommand = "zip -j0 {$this->tmpDir}/{$zipRemoteFileName} ";

		$basePath = $this->getAbsolutePath($this->rootDir . "/" . $this->homeDir);
		$outputPath = $this->tmpDir . "/" . $zipRemoteFileName;
		$filesCount = count($pathArray);

		if (!is_writable(realpath(dirname($outputPath)))) {
		    throw new \Exception(__METHOD__ . " - Directory " . $outputPath . " is not writable");
		}

		if ($filesCount >= 1) {
			foreach($pathArray as $file) {
				$zipCommand .= "'{$this->rootDir}/{$this->homeDir}/{$file}' ";
				$zipCommand = preg_replace("/\/$/", "", $zipCommand);
			}

			$zipCommand = str_replace("//", "/", $zipCommand);
			exec($zipCommand, $array);

			return $this->tmpDir . "/" . $zipRemoteFileName;
		} else {
			throw new \Exception(__METHOD__ . " - No file submited");
		}
	}

	public function makeZipArchive($pathArray)
	{
		$zip = new \ZipArchive();
		$date = date("ymdHis");
		$zipRemoteFileName = "files{$date}.zip";

		$basePath = $this->getAbsolutePath($this->rootDir . "/" . $this->homeDir);
		$outputPath = $this->tmpDir . "/" . $zipRemoteFileName;
		$filesCount = count($pathArray);

		if (!$zip->open($outputPath, \ZipArchive::CREATE)) {
		    exit("Impossible d'ouvrir le fichier <$filename>\n");
		}

		if ($filesCount > 1) {
			foreach($pathArray as $file) {
				$zip->addFile($basePath . "/" . $file, basename($file));
			}

			$zip->close();

			return $this->tmpDir . "/" . $zipRemoteFileName;
		} else {
			throw new \Exception(__METHOD__ . " - No file submited");
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

	public function getConfig()
	{
		return array_merge(
			$this->config,
			["currentPath" => $this->getCurrentPath()]
		);
	}
}

?>
