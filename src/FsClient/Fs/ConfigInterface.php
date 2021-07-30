<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 08/04/2017
 * Time: 18:15
 */

namespace Titiyoyo\FsClient\Fs;


interface ConfigInterface
{
    public function getRootDir();
    public function getTmpDir();
    public function setRootDir($dir);
    public function setTmpDir($dir);
    public function getDefaultPermissions();
    public function setDefaultPermissions($mode);
    public function validateConfiguration($params);
}
