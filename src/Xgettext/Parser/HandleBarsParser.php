<?php

namespace Xgettext\Parser;

class HandleBarsParser extends AbstractParser implements ParserInterface
{
    public function getFuncRegex()
    {
        return '`(' . implode('|', array_keys($this->keywords)) . ')(.*?["\'])\s*\}{2}`';
    }

    public function getArgsRegex()
    {
        return '`(["\'])(.*?)\1`';
    }
}
