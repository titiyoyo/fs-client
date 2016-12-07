<?php

spl_autoload_register(
    function() {
        include_once realpath('fsclient/Tertere/Fs/ClientBase.php');
        include_once realpath('fsclient/Tertere/Fs/ItemBase.php');
        include_once realpath('fsclient/Tertere/Utilities.php');
        include_once realpath('fsclient/Tertere/Fs/Ftp/FtpClient.php');
        include_once realpath('fsclient/Tertere/Fs/Ftp/FtpItem.php');
        include_once realpath('fsclient/Tertere/Fs/Local/LocalClient.php');
    }
);