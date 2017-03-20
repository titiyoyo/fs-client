<?php

spl_autoload_register(
    function() {
    	include_once realpath('./src/Fs/FsClientLogger.php');
		include_once realpath('./src/Fs/ItemBase.php');
        include_once realpath('./src/Fs/FsClientBase.php');
        include_once realpath('./src/Utilities.php');
        include_once realpath('./src/Fs/Ftp/FtpClient.php');
        include_once realpath('./src/Fs/Ftp/FtpItem.php');
        include_once realpath('./src/Fs/Local/LocalClient.php');
        include_once realpath('./src/Fs/Local/LocalItem.php');
    }
);