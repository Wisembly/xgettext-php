<?php

namespace Xgettext\Parser;

class JavascriptParser extends AbstractRegexParser implements ParserInterface
{
    public function extractCalls($line)
    {
        $calls = array();
        preg_match_all('`((' . implode('|', array_keys($this->keywords)) . '))\(((?:[^()]*\([^()]*\))*[^()]*)\)`', $line, $matches);

        foreach ($matches[1] as $index => $keyword) {
            $calls[] = array(
                'keyword'   => $keyword,
                'arguments' => $matches[3][$index],
            );
        }

        return $calls;
    }

    public function extractArguments($arguments)
    {
        $args = array();
        preg_match_all('`(?:\s*([\'"]))(.+?)(?=(?<!\\\)\1)\1`', $arguments, $matches);

        foreach ($matches[1] as $index => $delimiter) {
            $args[] = array(
                'delimiter' => $delimiter,
                'arguments'  => $matches[2][$index],
            );
        }

        return $args;
    }
}
