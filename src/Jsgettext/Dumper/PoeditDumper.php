<?php

namespace Jsgettext\Dumper;

use Jsgettext\Poedit\PoeditFile,
    Jsgettext\Poedit\PoeditString;

class PoeditDumper implements DumperInterface
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
    *   Dump PoeditFile into .po file
    *
    *   @param PoeditFile   $file
    *   @param string       $filename
    *   @return boolean
    */
    public function dump(PoeditFile $file, $filename = null)
    {
        $filename = null !== $filename ? $filename : $this->file;
        $content = $file->getHeaders() . PHP_EOL . PHP_EOL;

        foreach ($file->getStrings() as $string) {
            $content .= $string;
        }

        // ensure that path exists
        $this->mkdirr(substr($filename, 0, strrpos($filename, '/')));

        return false !== file_put_contents($filename, $content);
    }

    private function mkdirr($pathname, $mode = 0777)
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
        if ($this->mkdirr($next_pathname, $mode)) {
            if (!file_exists($pathname)) {
                return mkdir($pathname, $mode);
            }
        }

        return false;
    }
}