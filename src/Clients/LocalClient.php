<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 02/04/2017
 * Time: 17:42
 */

namespace Tertere\Clients;

use Tertere\Fs\Local\LocalConfig;
use Tertere\FsClient\User\FsUser;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface

class LocalClient extends AbstractClient
{
    private $fsObj;
    private $currentDir;

    public function __construct($configSettings, LoggerInterface $logger) {
        $config = new LocalConfig($configSettings);
        $user = new FsUser($config->getHomeDir(), [UserRight::ADMIN, UserRight::USER]);
        $this->fsObj = new Filesystem();
        parent::__construct($user, $config, $logger);
    }

    public function isConfigured()
    {
        return $this->config->isConfigured();
    }

    public function browse($path) {

    }

    public function mkdir($path) {
        try {
            if (!$this->fsObj->exists($path) && $this->hasPermission($path)) {
                $this->fsObj->mkdir($path);
            }
        } catch (IOExceptionInterface $e) {
            echo "An error occurred while creating your directory at ".$e->getPath();
        }
    }

    public function move($filePath, $targetPath) {

    }

    public function delete($path) {
        try {
            if ($this->hasPermission($path)) {
                return unlink($this->user->getHomeDir() . "/" . $path);
            }
        } catch (IOExceptionInterface $e) {
            echo "An error occurred while creating your directory at ".$e->getPath();
        }
    }

    public function get($path) {


        throw new \Exception(__METHOD__ . " - File " . $path . " does not exist on line " . __LINE__ . " in file " . __FILE__, 1);
    }

    public function getRootDir() {
        return $this->config->getRootDir();
    }

    public function getTmpDir() {
        return $this->config->getTmpDir();
    }

    public function getHomeDir() {
        return $this->user->getHomeDir();
    }
}