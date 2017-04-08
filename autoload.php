<?php

spl_autoload_register(
    function () {
        include_once realpath('./src/FsClient/Clients/AbstractClient.php');
        //include_once realpath('./src/FsClient/Clients/FtpClient.php');
        include_once realpath('./src/FsClient/Clients/LocalClient.php');

        include_once realpath('./src/FsClient/Fs/AbstractItem.php');
        include_once realpath('./src/FsClient/Fs/AbstractDirectory.php');
        include_once realpath('./src/FsClient/Fs/AbstractConfig.php');
//        include_once realpath('./src/FsClient/Fs/Ftp/FtpItem.php');
//        include_once realpath('./src/FsClient/Fs/Ftp/FtpConfig.php');
//        include_once realpath('./src/FsClient/Fs/Ftp/FtpDirectory.php');
        include_once realpath('./src/FsClient/Fs/Local/LocalItem.php');
        include_once realpath('./src/FsClient/Fs/Local/LocalConfig.php');
        include_once realpath('./src/FsClient/Fs/Local/LocalDirectory.php');

        include_once realpath('./src/FsClient/Exception/FsClientConfigException.php');

        include_once realpath('./src/FsClient/User/User.php');
        include_once realpath('./src/FsClient/User/UserRight.php');
        include_once realpath('./src/FsClient/User/Manager.php');

        include_once realpath('./src/FsClient/Utilities/File.php');
        include_once realpath('./src/FsClient/Utilities/Helpers.php');
        include_once realpath('./src/FsClient/Utilities/Http.php');
        include_once realpath('./src/FsClient/Utilities/Logger.php');
        include_once realpath('./src/FsClient/Utilities/MimeTypes.php');
        include_once realpath('./src/FsClient/Utilities/Validation.php');
    }
);
