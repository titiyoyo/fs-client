<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 20/03/2017
 * Time: 08:07
 */

namespace Tertere\FsClient\User;

class FsUser
{
    protected $homeDir;
    protected $rights;

    public function __construct($homeDir, array $rights)
    {

    }

    public function getRights()
    {
        return $this->rights;
    }
}