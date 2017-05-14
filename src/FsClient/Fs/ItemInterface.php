<?php

namespace Tertere\FsClient\Fs;

interface ItemInterface
{
    public function isDir();
    public function isLink();
    public function isFile();

    public function getType();
    public function getPath();
    public function getMimeType();
    public function getDirname();
    public function getFilename();
    public function getSize();
    public function getExtension();
    public function getCreationDate();
    public function getModificationDate();
    public function getRelativePathTo($path);

    public function toArray();
    public function toJson();
    public function rename($newFilename);
    public function delete(): bool;
}