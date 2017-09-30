<?php

namespace Tertere\FsClient\Fs;

interface DirectoryInterface
{
    public static function create($path);
    public function get($idx);
    public function getByName($name);
    public function delete(): bool;
    public function validatePath($path): bool;
    public function toArray();
}