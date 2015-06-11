<?php

namespace Xgettext;

use \Exception,
    \InvalidArgumentException;

use Xgettext\Poedit\PoeditFile,
    Xgettext\Dumper\PoeditDumper,
    Xgettext\Parser\PoeditParser;

class Scraper
{
    public $poeditFile;

    public function __construct($file, $cli = false)
    {
        $this->cli = $cli;

        $poeditParser = new PoeditParser($file);

        $this->poeditFile = $poeditParser->parse();
    }

    public function scrap()
    {

    }
}
