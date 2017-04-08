<?php

namespace Tertere\FsClient\User;

use Tertere\FsClient\Utilities\Logger;
use Tertere\FsClient\User\User;

class Manager
{
    const MODE_LOCAL = 1;
    const MODE_FTP = 2;

    protected $client;
    protected $logger;
    protected $user;
    protected $mode;

    public function __construct($mode, $user, Logger $logger)
    {
        $this->logger = $logger;
        $this->mode = $mode;
    }

    public function getClient()
    {
        return $this->client;
    }
}
