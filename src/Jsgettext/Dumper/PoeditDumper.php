<?php

namespace Jsgettext\Dumper;

use \InvalidArgumentException;

use Jsgettext\File\File,
    Jsgettext\Poedit\PoeditFile,
    Jsgettext\Poedit\PoeditString;

class PoeditDumper implements DumperInterface
{
    private $file;

    public function __construct($file)
    {
        if (empty($file)) {
            throw new InvalidArgumentException('You must provide a valid file to dump the translations', 1);
        }

        $this->file = $file;
    }

    /**
    *   Dump PoeditFile into .po file
    *
    *   @param PoeditFile   $file
    *   @param string       $filename
    *   @param boolean      $sort       if enabled, sort strings and their comments. implemented to avoid too many git conflicts
    *   @param string       $enc
    *
    *   @return boolean
    */
    public function dump(PoeditFile $file, $filename = null, $sort = false, $enc = 'UTF-8')
    {
        $filename = null !== $filename ? $filename : $this->file;
        $content = $file->getHeaders() . PHP_EOL . PHP_EOL;

        $content .= "\"Content-Type: text/plain; charset=" . $enc . "\\n\"" . PHP_EOL;
                    // "\"X-Poedit-SourceCharset: UTF-8\\n\"" . PHP_EOL;

        $strings = true === $sort ? $file->sortStrings()->getStrings() : $file->getStrings();

        foreach ($strings as $string) {
            $content .= true === $sort ? $string->sortReferences()->sortComments()->sortExtracteds()->sortFlags() : $string;
        }

        // ensure that path and file exists
        File::mkdirr(substr($filename, 0, strrpos($filename, '/')));

        return false !== file_put_contents($filename, $content);
    }
}
