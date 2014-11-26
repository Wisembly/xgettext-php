<?php

namespace Xgettext\Parser;

interface ParserInterface
{
    // parse string if given, $this->file if string is null
    public function parse($string = null);
}
