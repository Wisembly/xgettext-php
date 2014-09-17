<?php

namespace Jsgettext\Dumper;

use Jsgettext\File\File,
    Jsgettext\Poedit\PoeditFile,
    Jsgettext\Poedit\PoeditString;

class JsonDumper implements DumperInterface
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
    *   Dump PoeditFile translated keys into key/value .json file
    *
    *   @param PoeditFile   $file
    *   @param string       $filename
    *
    *   @return boolean
    */
    public function dump(PoeditFile $file, $filename = null)
    {
        $filename = null !== $filename ? $filename : $this->file;
        $content = array();

        $strings = $file->getTranslated();

        foreach ($strings as $string) {
            $content[$string->getKey()] = $string->getValue();
        }

        $content = json_encode($content, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES);

        // ensure that path and file exists
        File::mkdirr(substr($filename, 0, strrpos($filename, '/')));

        return false !== file_put_contents($filename, $content);
    }
}
