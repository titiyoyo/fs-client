<?php

namespace Tertere\FsClient\Clients;

use Psr\Log\LoggerInterface;
use Tertere\FsClient\Fs\ConfigInterface;

abstract class AbstractClient
{
    protected LoggerInterface $logger;
    protected ConfigInterface $config;

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
        $this->logger = $logger;
    }

    public function getConfig() :ConfigInterface
    {
        return $this->config;
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

    public abstract function isAllowed($path): bool;

    public function getRootDir()
    {
        return $this->config->getRootDir();
    }

    public function getTmpDir()
    {
        return $this->config->getTmpDir();
    }
}
