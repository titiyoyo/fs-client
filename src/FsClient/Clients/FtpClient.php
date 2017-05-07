<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 02/04/2017
 * Time: 17:42
 */

namespace Tertere\FsClient\Clients;

use Tertere\FsClient\Clients\AbstractClient;

class FtpClient extends AbstractClient
{
    const MODE = 2;

    public function getRelativePath($file, $path)
    {
        // TODO: Implement getRelativePath() method.
    }

    public function get($file)
    {
        // TODO: Implement get() method.
    }

    public function mkdir($path)
    {
        // TODO: Implement mkdir() method.
    }

    public function browse($path)
    {
        // TODO: Implement browse() method.
    }

    public function isConfigured()
    {
    }

    public function getAbsolutePath($path)
    {
    }
}
