<?php

namespace Xgettext\Parser;

class JavascriptParser extends AbstractRegexParser implements ParserInterface
{
    // public function extractCalls($line)
    // {
    //     // catch everything looking like keyword(<arguments>)
    //     preg_match_all('`((' . implode('|', array_keys($this->keywords)) . '))\(,?\h*((["\'])(?:\\\4|(?!\4|\n).)*\4),?\s*((?3))?`', $line, $matches);

    //     $calls = array();

    //     foreach ($matches[1] as $index => $keyword) {
    //         $calls[] = array(
    //             'keyword'   => $keyword,
    //             'arguments' => $matches[3][$index] . (isset($matches[5][$index]) ? $matches[5][$index] : ''), // weird regex behavior if two strings arguments does not have the same delimiter
    //         );
    //     }

    //     return $calls;
    // }

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
