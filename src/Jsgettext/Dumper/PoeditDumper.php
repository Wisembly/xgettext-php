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
        $content = $file->getHeaders() . PHP_EOL . PHP_EOL;
        foreach ($file->getStrings() as $string) {
            $content .= $string;
        }

        return false !== file_put_contents(null !== $filename ? $filename : $this->file, $content);
    }
}