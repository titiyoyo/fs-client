<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 20/03/2017
 * Time: 13:07
 */

namespace Tertere\FsClient\User;

class UserRight
{
    const ADMIN = "admin";
    const USER = "user";

    private static $all = [
        self::ADMIN, self::USER
    ];

    public static function getAllRights()
    {
        return self::$all;
    }

    public static function rightExists($right)
    {
        foreach (self::$all as $item) {
            if ($item === $right) {
                return true;
            }
        }

        return false;
    }
}
