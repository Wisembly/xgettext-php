<?php

namespace Jsgettext\File;

class File
{
    public static function mkdirr($pathname, $mode = 0777)
    {
        // Check if file already exists
        if (is_dir($pathname) || empty($pathname)) {
            return true;
        }

        // Ensure a file does not already exist with the same name
        $pathname = str_replace(array('/', ''), DIRECTORY_SEPARATOR, $pathname);
        if (is_file($pathname)) {
            return false;
        }

        // Crawl up the directory tree
        $next_pathname = substr($pathname, 0, strrpos($pathname, DIRECTORY_SEPARATOR));
        if (self::mkdirr($next_pathname, $mode)) {
            if (!file_exists($pathname)) {
                return mkdir($pathname, $mode);
            }
        }

        return false;
    }
}
