<?php

spl_autoload_register(
    function() {
        include_once realpath('vendor/tertere/Tertere/Fs/ClientBase.php');
        include_once realpath('vendor/tertere/Tertere/Fs/ItemBase.php');
        include_once realpath('vendor/tertere/Tertere/Utilities.php');
        include_once realpath('vendor/tertere/Tertere/Fs/Ftp/FtpClient.php');
        include_once realpath('vendor/tertere/Tertere/Fs/Ftp/FtpItem.php');
        include_once realpath('vendor/tertere/Tertere/Fs/Local/LocalClient.php');

     //    include_once 'Tertere/Fs/ClientBase.php';
     //    include_once 'Tertere/Fs/ItemBase.php';
    	// include_once 'Tertere/Utilities.php';
    	// include_once 'Tertere/Fs/Ftp/FtpClient.php';
    	// include_once 'Tertere/Fs/Ftp/FtpItem.php';
     //    include_once 'Tertere/Fs/Local/LocalClient.php';
    }
);