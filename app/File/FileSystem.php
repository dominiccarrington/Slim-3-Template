<?php
namespace App\File;

class FileSystem
{
    public static function foreachFileInFolder($folder, $ext, $run)
    {
        $ff = scandir($folder);
        $ff = array_slice($ff, 2);

        foreach ($ff as $f) {
            if (is_dir($folder . "/" . $f)) {
                self::foreachFolder($folder . "/" . $f, $ext, $run);
            } else {
                $fileExtension = explode(".", $f);
                $fileExtension = isset($fileExtension[1]) ? $fileExtension[1] : "";
                if (is_array($ext) && in_array($fileExtension, $ext)) {
                    if (is_callable($run)) {
                        // Function was passed
                        $run($folder . "/" . $f);
                    } elseif (is_array($run)) {
                        call_user_method($run[1], $run[0]);
                    }
                }
            }
        }
    }

    public static function foreachFolder($base, $exclude, $run, $fullName = true)
    {
        $ff = scandir($base);
        $ff = array_slice($ff, 2);

        foreach ($ff as $f) {
            if (is_dir($base . "/" . $f) && !in_array($f, $exclude)) {
                if ($fullName) {
                    $run($base . "/" . $f);
                } else {
                    $run($f);
                }
            }
        }
    }

    public static function replaceIn($path, $search, $replace)
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}
