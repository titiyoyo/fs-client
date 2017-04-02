<?php

spl_autoload_register(
    function() {
		include_once realpath('./src/FsClient/Clients/ItemBase.php');
		include_once realpath('./src/FsClient/Clients/FsClientBase.php');
		include_once realpath('./src/FsClient/Clients/Ftp/FtpItem.php');
        include_once realpath('./src/FsClient/Clients/Ftp/FtpClient.php');
        include_once realpath('./src/FsClient/Clients/Local/LocalItem.php');
        include_once realpath('./src/FsClient/Clients/Local/LocalClient.php');
        include_once realpath('./src/FsClient/User/FsUser.php');
        include_once realpath('./src/FsClient/User/UserRight.php');
        include_once realpath('./src/FsClient/Manager.php');
        include_once realpath('./src/Utilities/Http.php');
        include_once realpath('./src/Utilities/Logger.php');
        include_once realpath('./src/Utilities/MimeTypes.php');
        include_once realpath('./src/Utilities/Validation.php');
    }
);