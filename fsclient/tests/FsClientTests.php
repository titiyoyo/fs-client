<?php

namespace Tertere\Test;
use \PHPUnit\Framework\TestCase;
use \Tertere\Fs\Local\LocalClient as LocalClient;

class FsClientTest extends TestCase
{
	const rootFolder = "./tmp/fsclient/files";
	const tmpFolder = "./tmp/fsclient/tmp";

	private $folders = [
		self::rootFolder,
		self::tmpFolder,
		self::rootFolder . "/user1",
		self::rootFolder . "/user1/dir1",
		self::rootFolder . "/user1/dir2",
		self::rootFolder . "/user1/dir3"
    ];

    private $files = [
		self::rootFolder . "/user1/dir1/file1.txt",
		self::rootFolder . "/user1/dir1/file2.txt",
		self::rootFolder . "/user1/dir1/file3.txt",
		self::rootFolder . "/user1/dir2/file1.txt",
		self::rootFolder . "/user1/dir2/file2.txt",
		self::rootFolder . "/user1/dir2/file3.txt",
		self::rootFolder . "/user1/dir3/file1.txt",
		self::rootFolder . "/user1/dir3/file2.txt",
		self::rootFolder . "/user1/dir3/file3.txt"
	];


	public function testList()
	{
		$this->createFileStructure();
		$a = new LocalClient($this->getConfig());
	}


	public function createFileStructure()
    {
    	foreach($this->folders as $folder) {
    		$curFolder = realpath(".") . $folder;
    		if (!file_exists($curFolder)) {
    			mkdir($curFolder, 2777, true);
    		}
    	}
    	
    	foreach($this->files as $file) {
    		$curFile = realpath(".") . $file;
    		if (!file_exists($curFile)) {
    			touch($curFile);
    		}
    	}
    }

    public function cleanFiles()
    {
    	array_map("unlink", $this->files);
    	array_map("unlink", $this->folders);
    }

    private function getConfig($userFolder)
    {
    	return [
			"root_dir" => $this->rootFolder,
			"tmp_dir" => $this->tmpFolder,
			"home_dir" => $userFolder
    	];
    }
}

?>