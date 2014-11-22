<?php

namespace Xgettext;

use \Exception,
    \InvalidArgumentException;

use Xgettext\Poedit\PoeditFile,
    Xgettext\Dumper\PoeditDumper,
    Xgettext\Parser\JavascriptParser;

class Xgettext
{
    public function __construct(array $files, $output, array $keywords = array('_'), $enc = 'UTF-8', $cli = false)
    {
        $this->cli = $cli;

        if (empty($files)) {
            throw new InvalidArgumentException('You did not provide any input file.');
        }

        if (empty($output)) {
            throw new InvalidArgumentException('You did not provide any output file.');
        }

        $poeditFile = new PoeditFile();

        foreach ($files as $file) {
            $javascriptParser = new JavascriptParser($file, $keywords);
            $poeditFile->addStrings($javascriptParser->parse());
        }

        $poeditDumper = new PoeditDumper($output);
        $poeditDumper->dump($poeditFile, null, false, $enc);
    }
}
