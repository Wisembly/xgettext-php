<?php

namespace Jsgettext\Parser;

class JavascriptParser extends AbstractParser implements ParserInterface
{
    public function getFuncRegex()
    {
        return '`(' . implode('|', array_keys($this->keywords)) . ')\(([^)]*)\)`';
    }

    public function getArgsRegex()
    {
        return '`(?:\s*([\'"]))(.+?)(?=(?<!\\\)\1)\1`';
    }
}
