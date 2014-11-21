<?php

namespace Jsgettext\Parser;

interface ParserInterface
{
    // parse string if given, $this->file if string is null
    public function parse($string = null);

    // retrieve the regex that finds the gettext calls in files
    public function getFuncRegex();

    // retrieve the regex that analyze the arguments
    public function getArgsRegex();
}
