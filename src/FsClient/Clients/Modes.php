<?php

namespace Tertere\FsClient\Clients;

class Modes
{
    const MODE_FTP = FtpClient::MODE;
    const MODE_LOCAL = LocalClient::MODE;
    const MODE_DEFAULT = self::MODE_LOCAL;
}