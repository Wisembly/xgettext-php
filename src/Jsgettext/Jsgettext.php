<?php

namespace Jsgettext;

use \Exception,
    \InvalidArgumentException;

use Jsgettext\Poedit\PoeditFile,
    Jsgettext\Dumper\PoeditDumper,
    Jsgettext\Parser\JavascriptParser;

class Jsgettext
{
    public function __construct(array $files, $output, array $keywords = array('_'), $cli = false)
    {
        $this->cli = $cli;

        if (empty($files)) {
            throw new InvalidArgumentException('You did not provide any input file.');
        }

        if (empty($output)) {
            throw new InvalidArgumentException('You did not provide any output file.');
        }

        $this->createOutputFile($output);

        $poeditFile = new PoeditFile();

        foreach ($files as $file) {
            $javascriptParser = new JavascriptParser($file, $keywords);
            $poeditFile->addStrings($javascriptParser->parse());
        }

        $poeditDumper = new PoeditDumper($output);
        $poeditDumper->dump($poeditFile);
    }

    private function createOutputFile($output)
    {
        @exec("mkdir -p `dirname {$output}`");
        @exec("touch {$output}");
    }
}