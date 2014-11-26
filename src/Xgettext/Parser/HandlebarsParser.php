<?php

namespace Xgettext\Parser;

// http://stackoverflow.com/questions/26725207/find-every-string-between-quotes-inside-specific-curly-braces
class HandlebarsParser extends AbstractRegexParser implements ParserInterface
{
    public function extractCalls($line)
    {
        $calls = array();
        preg_match_all('`(' . implode('|', array_keys($this->keywords)) . ')(.*?["\'])\s*\}{2}`', $line, $matches);

        foreach ($matches[1] as $index => $keyword) {
            $calls[] = array(
                'keyword'   => $keyword,
                'arguments' => $matches[2][$index],
            );
        }

        return $calls;
    }

    public function extractArguments($arguments)
    {
        $args = array();
        preg_match_all('`(["\'])(.*?)\1`', $arguments, $matches);

        foreach ($matches[1] as $index => $delimiter) {
            $args[] = array(
                'delimiter' => $delimiter,
                'arguments'  => $matches[2][$index],
            );
        }

        return $args;
    }
}
