<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 20/03/2017
 * Time: 08:01
 */

namespace Tertere\Utilities;

class Http
{
    //force le téléchargement de fichiers
    //found on : http://davidwalsh.name/php-force-download

    public static function forceDownload($filename)
    {
        // required for IE, otherwise Content-disposition is ignored
        if(ini_get('zlib.output_compression'))
        { ini_set('zlib.output_compression', 'Off'); }

        // addition by Jorg Weske
        $file_extension                     = strtolower(substr(strrchr($filename,"."),1));
        $taille                             = filesize($filename);

        switch( $file_extension )
        {
            case "gif":                     $ctype = "image/gif"; break;
            case "png":                     $ctype = "image/png"; break;
            case "jpeg": case "jpg":        $ctype = "image/jpg"; break;
            case "pdf":                     $ctype = "application/pdf"; break;
            case "exe":                     $ctype = "application/octet-stream"; break;
            case "zip":                     $ctype = "application/zip"; break;
            case "doc":                     $ctype = "application/msword"; break;
            case "xls":                     $ctype = "application/vnd.ms-excel"; break;
            case "ppt":                     $ctype = "application/vnd.ms-powerpoint"; break;
            default:                        $ctype = "application/force-download";
        }

        header("Pragma: public"); // required
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        //header("Cache-Control: private",false); // required for certain browsers
        header("Content-Type: $ctype");

        // change, added quotes to allow spaces in filenames, by Rajkumar Singh
        header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
        header("Content-Transfer-Encoding: binary");
        header("Retry-After: 5");
        header("Transfer-Encoding: gzip");
        header("Content-Length: ".$taille);
        ob_end_flush();
        readfile($filename);
        exit();
    }
}