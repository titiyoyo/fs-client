<?php

namespace Tertere\Test;
use \PHPUnit\Framework\TestCase;
use \Tertere\Fs\FsClientLogger as FsClientLogger;

class FsClientLoggerTest extends TestCase
{
	public function testLogger()
	{
		$logger = new FsClientLogger();
		$logger->error("ss");
	}
}

?>