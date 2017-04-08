<?php

namespace Tertere\Test;

use \PHPUnit\Framework\TestCase;
use Tertere\FsClient\Utilities\Logger;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Psr\Log\LoggerInterface;

class LoggerTest extends TestCase
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



    public function getMockLoggerInterface()
    {
        return $this->createMock(LoggerInterface::class);
    }

    public function getMockVarCloner()
    {
        return $this->createMock(VarCloner::class);
    }

    public function getMockCliDumper()
    {
        $stub = $this->createMock(CliDumper::class);

        return $stub;
    }
}
