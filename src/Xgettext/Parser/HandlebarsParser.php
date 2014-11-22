<?php

namespace Xgettext\Parser;

class HandlebarsParser extends AbstractParser implements ParserInterface
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
