<?php
/**
 * Created by PhpStorm.
 * User: terencepires
 * Date: 02/04/2017
 * Time: 15:28
 */

namespace Tertere\Utilities;


class File
{
    public static function createFileFromChunks($tmpDir, $filename, $totalSize, $totalChunks) {
        // count all the parts of this file
        $total_files_on_server_size = 0;
        $temp_total = 0;
        $chunkFiles = scandir($tmpDir);
        if (count($chunkFiles) - 2 == $totalChunks) {
            foreach(scandir($tmpDir) as $file) {
                $temp_total = $total_files_on_server_size;
                $tempfilesize = filesize($tmpDir . '/' . $file);
                $total_files_on_server_size = $temp_total + $tempfilesize;
            }

            // check that all the parts are present
            // If the Size of all the chunks on the server is equal to the size of the file uploaded.
            if ($total_files_on_server_size >= $totalSize) {
                if (($fp = fopen($tmpDir . "/" . $filename, 'w')) !== false) {
                    for ($i=1; $i<=$totalChunks; $i++) {
                        fwrite($fp, file_get_contents($tmpDir . '/' . $filename . '.part-' . $i));
                    }
                    fclose($fp);
                } else {
                    throw new \Exception(__METHOD__ . ' - cannot create the destination file ' . $filename . " on " . __LINE__ . " in file " . __FILE__);
                }
            }
        }
    }

    public static function chunkExists($tmpDir, $tmpSubDir, $filename, $chunkNumber) {
        $chunk_file = $tmpDir . '/' . $tmpSubDir . "/" . $filename . '.part' . $chunkNumber;
        return (bool)realpath($chunk_file);
    }
}