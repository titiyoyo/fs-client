<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 02/04/2017
 * Time: 17:42
 */

namespace Tertere\FsClient\Clients;

use Tertere\FsClient\Clients\AbstractClient;
use Tertere\FsClient\Fs\ItemInterface;

class FtpClient extends AbstractClient implements ClientInterface
{
    const MODE = 2;

    public function delete($path)
    {
        // TODO: Implement delete() method.
    }

    public function getRelativePath($file, $path): string
    {
        // TODO: Implement getRelativePath() method.
    }

    public function get($file): ItemInterface
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

    public function isConfigured(): bool
    {
    }

    public function getAbsolutePath($path): string
    {
    }
}
