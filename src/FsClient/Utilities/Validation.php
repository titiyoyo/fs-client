<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 20/03/2017
 * Time: 07:59
 */

namespace Titiyoyo\FsClient\Utilities;

class Validation
{
    /**
     * checks if $idx exists in $array and is not empty
     * @param $idx
     * @param $array
     * @return bool
     */
    public static function checkIdx($idx, $array)
    {
        if (isset($array[$idx]) && !empty($array[$idx])) {
            return true;
        }

        return false;
    }
}
