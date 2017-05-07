<?php

namespace Tertere\FsClient\Clients;

use Psr\Log\LoggerInterface;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\VarDumper;
use Tertere\FsClient\Fs\ConfigInterface;
use Tertere\FsClient\User\FsUser;
use Tertere\FsClient\Utilities\Logger;

abstract class AbstractClient
{
    protected $logger;
    protected $config;
    protected $user;

    abstract public function isConfigured();
    abstract public function get($file);
    abstract public function browse($path);
    abstract public function mkdir($path);
    abstract public function getRelativePath($file, $path);

    /**
        indexes list:
        config 		-> the whole $settingsArray parameters array
        rootDir 	-> files root dir (ftp or local)
        tmpDir 		-> local temp dir
        homeDir 	-> user root dir
        logger 		-> an sfLogger instance (optional)
    */
    public function __construct(ConfigInterface $config, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->logger = new Logger($logger, new VarCloner(), new CliDumper());
    }

    public function getConfig() :ConfigInterface
    {
        return $this->config;
    }

    public function hasPermission($path)
    {
        return $this->user->hasPermission($path);
    }

    public function getBreadcumbArray($path)
    {
        $absolutePath = realpath($this->rootDir);
        $homeDirAbsolutePath = realpath($absolutePath . "/" . $this->homeDir);
        $relativeHome = str_replace($absolutePath, "", $homeDirAbsolutePath);

        return
            explode("/",
                preg_replace(
                    "#^/?/#",
                    "",
                    str_replace($this->homeDir, "", $relativeHome . "/" . $path)
                )
            );
    }

    public function compress(array $pathArray)
    {
        $zip = new \ZipArchive();
        $date = date("ymdHis");
        $zipFileName = "files{$date}.zip";

        $outputPath = $this->getTmpDir() . "/" . $zipFileName;
        $filesCount = count($pathArray);

        if (!$zip->open($outputPath, \ZipArchive::CREATE)) {
            throw new \Exception("Impossible d'ouvrir le fichier <$zipFileName>\n");
        }

        if ($filesCount > 1) {
            foreach ($pathArray as $file) {
                $zip->addFile($this->getRootDir() . "/" . $file, basename($file));
            }

            $zip->close();

            return $this->getTmpDir() . "/" . $zipFileName;
        } else {
            throw new \Exception(__METHOD__ . " - No file submited");
        }
    }
}
