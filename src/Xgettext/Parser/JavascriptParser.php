<?php

namespace Xgettext\Parser;

class JavascriptParser extends AbstractRegexParser implements ParserInterface
{
    public function extractCalls($line)
    {
        $calls = $this->handleMultiline($line);

        if (count($calls)) {
            return $calls;
        }

        preg_match_all('`(' . implode('|', array_keys($this->keywords)) . ')\((.*?["\'].*)\s*`', $line, $matches);

        foreach ($matches[1] as $index => $keyword) {
            $calls[] = array(
                'keyword'   => $keyword,
                'arguments' => $matches[2][$index],
            );
        }

        return $calls;
    }

    // handle multiple calls in a single line.
    private function handleMultiline($line)
    {
        $calls = array();
        $keywords = array_keys($this->keywords);
        $splits = preg_split('`(' . implode('|', $keywords) . ')\(`', $line, -1, PREG_SPLIT_DELIM_CAPTURE);

        if (count($splits) > 3) {
            foreach ($splits as $index => $split) {
                if (in_array($split, $keywords)) {
                    $calls = array_merge($calls, $this->extractCalls($split . '(' . $splits[$index+1]));
                }
            }

            return $calls;
        }

        return array();
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
