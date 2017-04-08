<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 08/04/2017
 * Time: 09:54
 */

namespace Tertere\Test;

use \PHPUnit\Framework\TestCase;
use \Tertere\FsClient\Utilities\Validation;

class ValidationTest extends TestCase
{
    public function testCheckIdx()
    {
        $array = [
            "test", "toto" => 1
        ];

        $this->assertFalse(Validation::checkIdx("test", $array));
        $this->assertTrue(Validation::checkIdx("toto", $array));
    }
}
