<?php

namespace Tertere\Test;

use \PHPUnit\Framework\TestCase;
use \Tertere\FsClient\Utilities\Validation;

class ValidationTest extends TestCase
{
    /**
     * @covers Validation::checkIdx
     */
    public function testCheckIdx()
    {
        $array = [
            "test", "toto" => 1
        ];

        $this->assertFalse(Validation::checkIdx("test", $array));
        $this->assertTrue(Validation::checkIdx("toto", $array));
    }
}
