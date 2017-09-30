<?php

namespace Tertere\FsClient\Clients;

use Tertere\FsClient\Fs\ConfigInterface;
use Tertere\FsClient\Fs\DirectoryInterface;
use Tertere\FsClient\Fs\ItemInterface;

interface ClientInterface
{
    public function isConfigured(): bool;
    public function get($file): ItemInterface;
    public function browse($path): DirectoryInterface;
    public function mkdir($path);
    public function getRelativePath($file, $path): string;
    public function getConfig(): ConfigInterface;
    public function delete();
    public function rename($newName);
    public function getRootDir();
    public function getTmpDir();
    public function toArray();
}