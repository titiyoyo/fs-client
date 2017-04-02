<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 20/03/2017
 * Time: 07:59
 */

namespace Tertere\Utilities;

class Validation
{
    public static function checkIdx($idx, $array)
    {
        if(isset($array[$idx]) && !empty($array[$idx])) {
            return true;
        }

        return false;
    }


}