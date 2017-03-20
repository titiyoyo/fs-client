<?php

namespace Tertere\Test;
use \PHPUnit\Framework\TestCase;
use \Tertere\Fs\Local\LocalClient as LocalClient;

class LocalClientTest extends TestCase
{
	const rootFolder = "./tmp/files";
	const tmpFolder = "./tmp/tmp";

	private $commonFolders = [
		self::rootFolder,
		self::tmpFolder
	];

	private $userFolders = [
		"user1",
		"user2",
		"user3"
    ];

    private $userFiles = [
		"file1.txt",
		"file2.txt",
		"file3.txt",
	];

	public function testPermissions() 
	{
		$this->init();

		$a = new LocalClient($this->getConfig(realpath(".")));

		$this->cleanFiles(realpath(self::rootFolder));
		$this->cleanFiles(realpath(self::tmpFolder));
	}

	public function init()
	{
		$oldmask = umask(0);

		if (!realpath(self::rootFolder)) {
			mkdir(self::rootFolder, 2777, true);
			chmod(realpath(self::rootFolder), 2777);
		}

		if (!realpath(self::tmpFolder)) {
			mkdir(self::tmpFolder, 2777, true);
			chmod(realpath(self::tmpFolder), 2777);
		}

		foreach ($this->userFolders as $folder) {
			$this->createUserFilesAndFolders(realpath(self::rootFolder) . "/" . $folder);
		}

		umask($oldmask);
	}


	public function createUserFilesAndFolders($basedir)
    {
    	$oldmask = umask(0);

    	foreach($this->userFolders as $folder) {
    		$curFolder = realpath(self::rootFolder) . "/" . $basedir . "/" . $folder;
    		if (!file_exists($curFolder)) {
    			mkdir($curFolder, 2777, true);
    		}

    		if (file_exists($curFolder)) {
	    		foreach ($this->userFiles as $file) {
	    			touch($curFolder . "/" . $file);
	    		}
    		} else {
    			throw new Exception("Couldn't create file " . $file . " because folder " . $curFolder . " has not been created");
    		}
    	}

    	umask($oldmask);
    }

    public function cleanFiles($baseFolder)
    {
    	$baseFiles = scandir($baseFolder);
    	foreach($baseFiles as $curFile) {
    		$fullPath = $baseFolder . "/". $curFile;
    		if ($curFile != "." || $curFile != "..") {
    			if (is_dir($fullPath)) {
    				cleanFiles($fullPath);
    			} else {
    				unlink($fullPath);
    				// unlink($fullPath);
    			}
    		}
    	}
    }

    private function getConfig($userFolder)
    {
    	return [
			"root_dir" => self::rootFolder,
			"tmp_dir" => self::tmpFolder,
			"home_dir" => $userFolder
    	];
    }
}

?>