<?php

namespace Titiyoyo\FsClient\Clients;

use Titiyoyo\FsClient\Fs\ConfigInterface;
use Titiyoyo\FsClient\Fs\DirectoryInterface;
use Titiyoyo\FsClient\Fs\ItemInterface;

interface ClientInterface
{
    public function isConfigured(): bool;
    public function get($file): ItemInterface;
    public function browse($path): DirectoryInterface;
    public function mkdir($path);
    public function getRelativePath($file, $path): string;
    public function getConfig(): ConfigInterface;
    public function getRootDir();
    public function getTmpDir();
    public function toArray();
    public function clearTmpFiles();
}
