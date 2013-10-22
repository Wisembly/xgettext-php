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
            throw new InvalidArgumentException('You did not provide any input file.' . $this->getHelp());
        }

        if (empty($output)) {
            throw new InvalidArgumentException('You did not provide any output file.' . $this->getHelp());
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

    private function getHelp()
    {
        if (false === $this->cli) {
            return;
        }

        echo <<<EOT

Usage php jsgettext -o [OUTPUT] -k [KEYWORDS] [FILES]   
    -o [OUTPUT]
        specify the .po output file where the keys will be dumped
        eg: ../../file.po
    -k [KEYWORDS]
        specify the keywords used to find in the files the strings to be parsed
        eg: __ _ i18n_
    [FILES]
        files list to be parsed
        eg: ../file.js ../anotherfile.html ../still/anotherfile.js


EOT
        ;
    }

    private function createOutputFile($output)
    {
        @exec("mkdir -p `dirname {$output}`");
        @exec("touch {$output}");
    }
}