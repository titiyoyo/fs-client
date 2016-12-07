<?php

spl_autoload_register(
    function() {
        include_once realpath('fsclient/src/Fs/ClientBase.php');
        include_once realpath('fsclient/src/Fs/ItemBase.php');
        include_once realpath('fsclient/src/Utilities.php');
        include_once realpath('fsclient/src/Fs/Ftp/FtpClient.php');
        include_once realpath('fsclient/src/Fs/Ftp/FtpItem.php');
        include_once realpath('fsclient/src/Fs/Local/LocalClient.php');
    }
);