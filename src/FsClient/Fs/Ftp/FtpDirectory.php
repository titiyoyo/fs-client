<?php

namespace Tertere\FsClient\Fs\Ftp;

use Tertere\FsClient\Fs\AbstractDirectory;
use Tertere\FsClient\Fs\DirectoryInterface;
use Tertere\FsClient\Fs\Ftp\FtpItem;

class FtpDirectory extends AbstractDirectory implements DirectoryInterface
{
    /** @var int id de connexion au ftp */
    private $connId = null;
    /** @var id de connexion au ftp */
    private $isLogged = false;

    protected $ftpAddress;
    protected $ftpUsername;
    protected $ftpPassword;
    protected $ftpRootDir;
    protected $tmpDir;
    protected $userRootDir;

    /**
     * liste des index du $ftpSettings
     * - ftp_server_address     addresse du serveur ftp
     * - ftp_user_name          login du serveur
     * - ftp_pass               pass
     * - ftp_tmp_dir            chemin sur le serveur web pour le stockage des fichiers temporaires (lors du téléchargement de fichiers depuis le ftp)
     * - ftp_root_dir           chemin vers la racine du serveur ftp
     *
     * @param $ftpSettings array tableau des paramètres nécessaires à la classe
     */
    public function __construct($ftpSettings)
    {
        $this->setConfig($ftpSettings);
    }

    public function setConfig($ftpSettings)
    {
        $this->ftpAddress = isset($ftpSettings["ftp_server_address"]) ? $ftpSettings["ftp_server_address"] : null;
        $this->ftpUsername = isset($ftpSettings["ftp_user_name"]) ? $ftpSettings["ftp_user_name"] : null;
        $this->ftpPassword = isset($ftpSettings["ftp_pass"]) ? $ftpSettings["ftp_pass"] : null;
        $this->ftpRootDir = isset($ftpSettings["ftp_root_dir"]) ? $ftpSettings["ftp_root_dir"] : null;
        $this->userRootDir = isset($ftpSettings["user_root_dir"]) ? $ftpSettings["user_root_dir"] : null;
        $this->tmpDir = isset($ftpSettings["ftp_tmp_dir"]) ? $ftpSettings["ftp_tmp_dir"] : null;
        $this->connId = ftp_connect($this->ftpAddress);
        $this->isLogged = $this->connect();

        $this->config = $ftpSettings;
        $this->rootDir = $this->ftpRootDir;
        $this->tmpDir = $this->tmpDir;
        $this->homeDir = $this->ftpRootDir . "/" . $this->userRootDir;
    }

    public function getAbsolutePath($path)
    {
    }

    public function isConfigured()
    {
        return !is_null($this->connId) && $this->isLogged;
    }

    /**
     * opens a connection to the server with the specified parameters
     * @return bool
     * @throws Exception
     */
    private function connect()
    {
        $status = false;
        if (!empty($this->connId)) {
            if (ftp_login($this->connId, $this->ftpUsername, $this->ftpPassword)) {
                $status = ftp_chdir($this->connId, $this->ftpRootDir . "/" . $this->userRootDir);
                $status = $status && ftp_chdir($this->connId, basename($this->ftpRootDir) . "/" . $this->userRootDir);
            } else {
                throw new \Exception("Login error");
            }
        } else {
            throw new \Exception(__METHOD__ . " - FtpClient is not configured properly, please pass valid server settings!");
        }

        return $status;
    }

    /**
     * tests the connection to an ftp server
     * @param $ftpLogin string server login
     * @param $ftpPass string password for the login
     * @param $ftpAddress string the server's address
     *
     * @return bool
     */
    public static function test($parametersArray)
    {
        $ftpLogin = $parametersArray['login'];
        $ftpPass = $parametersArray['pass'];
        $ftpAddress = $parametersArray['address'];

        $result = false;

        try {
            $connId = ftp_connect($ftpAddress);

            if (!is_bool($connId)) {
                $result = ftp_login($connId, $ftpLogin, $ftpPass);
            }
        } catch (\Exception $ex) {
            $result = false;
        } finally {
            return $result;
        }
    }

    /**
     * renames a $file to $newName
     * @param $filePath string file path to the file
     * @param $newName string new filename
     */
    public function rename($filePath, $newName)
    {
        $fileInfo = explode("/", $filePath);
        $filename = $fileInfo[count($fileInfo) -1];
        $dirname = $this->getAbsolutePath(dirname($filePath));

        ftp_chdir($this->connId, $dirname);
        ftp_rename($this->connId, $filename, $newName);
    }

    /**
     * deletes a file or directory
     * @param $filePath string path to the file
     * @return bool
     */
    public function delete($filePath)
    {
        $path = preg_replace("/\/$/", "", $this->getAbsolutePath($filePath));

        return $this->ftp_rmdirr($path);
    }

    private function ftp_rmdirr($path)
    {
        if (!(@ftp_rmdir($this->connId, $path) || @ftp_delete($this->connId, $path))) {
            $list = @ftp_nlist($this->connId, $path);

            if (!empty($list)) {
                foreach ($list as $value) {
                    $this->ftp_rmdirr($path . "/" . $value);
                }
            }
        }

        if (@ftp_rmdir($this->connId, $path)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * uploads a file to a target directory
     *
     * @param $localFilePath string
     * @param $targetFilePath
     *
     * @internal param string $targetDirPath path to the target dir
     * @return bool
     */
    public function put($localFilePath, $targetFilePath)
    {
        $status = false;
        $remotePath = "/" . $this->userRootDir . "/" . $targetFilePath;

        if (is_file($localFilePath)) {
            $status = ftp_put(
                $this->connId,
                $remotePath,
                $localFilePath,
                $this->getTransferType($localFilePath)
            );
        }

        return $status;
    }

    /**
     * creates a folder on the server
     * @param $path string  path to the folder
     * @return bool
     */
    public function createDir($path)
    {
        $dirExists = null;
        $status = null;

        // on remplace tous les caractères non alphanumériques par rien
        $completePath = preg_replace("#[^a-zA-Z0-9_\-\/]#", "", $path);
        $completePath = preg_replace("/\/\//", "/", $completePath);

        /**
         * ALGO
         * si $path n'est pas nul, on regarde si le répertoire existe déjà sur le serveur
         * si c'est le cas, on ne le crée pas   -> on renvoit null
         * sinon, on le crée                    -> on renvoit true
         * si un problème arrive                -> on renvoit false
        */
        if ($path != "" && !is_null($path)) {
            $dirExists  = @ftp_chdir($this->connId, $completePath);

            if ($dirExists == false) {
                $status = ftp_mkdir($this->connId, $path) ? true : false;
            } else {
                $status = false;
            }
        }

        return $status;
    }

    /**
     * returns a list of files extracted from function ftp_rawlist
     * @param $path string from which to retrieve the fileslist
     * @param $parentHash string $parentHash sha1 hash from the parent dir
     * @return array
     */
    public function list($path, $parentHash = "")
    {
        $filesArray = array();

        if (!empty($this->connId)) {
            if (empty($path) || is_null($path)) {
                $path = "";
            }

            $filesArray = array();
            $files = ftp_rawlist($this->connId, str_replace(" ", "\\ ", $path));

            foreach ($files as $file) {
                if (!preg_match("/total/", $file)) {
                    $oItem = new FtpItem($file, $path, $parentHash);

                    if ($oItem->filename != "." && $oItem->filename != "..") {
                        $filesArray[$oItem->hash] = $oItem->getInfoArray();
                    }
                }
            }
        } else {
            throw new \Exception(__METHOD__ . " - empty connId, can't connect to ftp server");
        }

        return $filesArray;
    }

    /**
     * returns the needed ftp transfer type for $filePath
     * @param $filePath string the path to the file to be transfered
     *
     * @return int
     */
    public static function getTransferType($filePath)
    {
        // return mime type ala mimetype extension
        $finfo = finfo_open(FILEINFO_MIME);

        //check to see if the mime-type starts with 'text'
        $result = substr(finfo_file($finfo, $filePath), 0, 4) == 'text' ? FTP_ASCII : FTP_BINARY;

        // closing handle
        finfo_close($finfo);

        return $result;
    }

    /**
        TODO
    */
    public function getFullPath($path)
    {
    }


    /**
        TODO
    */
    public function move($localPath, $targetPath)
    {
    }

    public function validatePath($path): bool
    {
        // TODO: Implement validatePath() method.
    }
}

