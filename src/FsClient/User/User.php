<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 20/03/2017
 * Time: 08:07
 */

namespace Tertere\FsClient\User;

use \Exception;

class User
{
    protected $homeDir;
    protected $rights;

    public function __construct($homeDir, array $rights)
    {
        if (!file_exists($homeDir)) {
            throw new Exception(__METHOD__ . " - directory " . $homeDir . " does not exist");
        }

        if (!is_writable($homeDir) || !is_readable($homeDir)) {
            throw new Exception(__METHOD__ . " - directory " . $homeDir . " is not readable or writable");
        }

        $this->homeDir = $homeDir;
    }

    public function hasPermission($path)
    {
        if ($this->hasRight(UserRight::ADMIN)) {
            return true;
        }
    }

    public function isAdmin()
    {
        return $this->hasRight(UserRight::ADMIN);
    }

    public function hasRight($right)
    {
        foreach ($this->rights as $curRight) {
            if ($curRight === $right) {
                return true;
            }
        }

        return false;
    }

    public function getRights()
    {
        return $this->rights;
    }
}
