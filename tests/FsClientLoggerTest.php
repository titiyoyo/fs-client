<?php

namespace Tertere\Test;
use \PHPUnit\Framework\TestCase;
use Tertere\Utilities\Logger;

class FsClientLoggerTest extends TestCase
{
    public function testLogger()
	{
        $loggerInterfaceMock = $this->createMock("LoggerInterface");
        $logger = new Logger($loggerInterfaceMock);
        var_dump(
            $logger->dumpArray([
                2,3,4,5
            ])
        );

	}
}

?>