<?php

namespace Tertere\Test;
use \PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Exception;
use Tertere\Utilities\Logger;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;

class FsClientLoggerTest extends TestCase
{
    public function testLogger()
	{
        $logger = new Logger(
            $this->getMockLoggerInterface(),
            $this->getMockVarCloner(),
            $this->getMockCliDumper()
        );

        $exception = new \Exception("test");
        $exceptionDump = $logger->dumpException($exception);
        $this->assertStringStartsWith("test", $exceptionDump);

        $logger->debug("test");
        $this->assertTrue(true);

        $logger->error("test");
        $this->assertTrue(true);

        $logger->info("test");
        $this->assertTrue(true);
	}



	public function getMockLoggerInterface() {
        return $this->getMockBuilder("Psr\\Log\\LoggerInterface")
            ->setMethods([
                "debug",
                "info",
                "error",
                "log"
            ])
            ->getMock();
    }

	public function getMockVarCloner() {
        return $this->getMockBuilder(VarCloner::class)
            ->setConstructorArgs([])
            ->setMethods([
                "cloneVar"
            ])
            ->getMock();
    }

    public function getMockCliDumper() {
        $stub = $this->getMockBuilder(CliDumper::class)
            ->setConstructorArgs([])
            ->setMethods([
                "dump"
            ])
            ->getMock();
        $stub->method("dump")
            ->willReturn("Array");

        return $stub;
    }
}

?>