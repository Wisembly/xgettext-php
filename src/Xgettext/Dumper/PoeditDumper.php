<?php

namespace Xgettext\Dumper;

use \InvalidArgumentException;

use Xgettext\File\File,
    Xgettext\Poedit\PoeditFile,
    Xgettext\Poedit\PoeditString;

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
    *   @param string       $charset
    *
    *   @return boolean
    */
    public function dump(PoeditFile $file, $filename = null, $sort = false, $charset = 'UTF-8', $noComments = false)
    {
        $filename = null !== $filename ? $filename : $this->file;
        $content = $file->getHeaders() . PHP_EOL;
        $content .= "\"Content-Type: text/plain; charset=" . $charset . "\\n\"" . PHP_EOL . PHP_EOL;

        $strings = true === $sort ? $file->sortStrings()->getStrings() : $file->getStrings();

        foreach ($strings as $string) {
            $content .= true === $sort ? $string->sortReferences()->sortComments()->sortExtracteds()->sortFlags()->dump($noComments) : $string->dump($noComments);
        }

        // ensure that path and file exists
        File::mkdirr(substr($filename, 0, strrpos($filename, '/')));

        return false !== file_put_contents($filename, $content);
    }
}
