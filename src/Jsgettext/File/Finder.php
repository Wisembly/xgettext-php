<?php

namespace Jsgettext\File;

use \InvalidArgumentException;
use \RecursiveDirectoryIterator,
    \RecursiveIteratorIterator;

class Finder
{
    public static function findr($path, $extensions = array('.js', '.html'))
    {
        $files = [];

        if (!is_dir($path)) {
            throw new InvalidArgumentException("A valid directory path must be given here");
        }

        $di = new RecursiveDirectoryIterator($path);

        foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
            if (1 === preg_match('/\.[0-9a-z]+$/i', $filename, $matches) && in_array($matches[0], $extensions)) {
                $files[] = $filename;
            }
        }

        return $files;
    }
}
