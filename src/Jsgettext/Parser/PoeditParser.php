<?php

namespace Jsgettext\Parser;

use \InvalidArgumentException;

use Jsgettext\Poedit\PoeditString,
    Jsgettext\Poedit\PoeditFile;

class PoeditParser implements ParserInterface
{
    protected $file;
    protected $poEditFile;

    public function __construct($file)
    {
        if (empty($file)) {
            throw new InvalidArgumentException('You must provide a valid file to be parsed', 1);
        }

        $this->file = $file;
        $this->poEditFile = new PoeditFile();
    }

    /**
    *   Parse a .po file and return a PoeditFile
    *   @return PoeditFile
    */
    public function parse()
    {
        $strings = array();
        $content = file_get_contents($this->file);

        if (false === $content) {
            throw new InvalidArgumentException('You must provide a valid file to be parsed', 1);
        }

        $parts = preg_split('#(\r\n|\n){2}#', $content, -1, PREG_SPLIT_NO_EMPTY);

        $parts = $this->parseHeaders($parts);

        foreach ($parts as $part) {
            $this->parsePart($part);
        }

        return $this->poEditFile;
    }

    private function parseHeaders($parts)
    {
        if (count($parts) && false !== strpos($parts[0], 'msgid ""')) {
            $this->poEditFile->setHeaders(array_shift($parts));
        }

        return $parts;
    }

    private function parsePart($part)
    {
        $string = $this->parseMessage($part);

        // parse comments
        preg_match_all('#^\\#(  |\. |: |, )(.*?)$#m', $part, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            switch ($match[1]) {
                case '  ':
                    $string->addComment($match[2]);
                break;
                case '. ':
                    $string->addExtracted($match[2]);
                break;
                case ': ':
                    $string->addReference($match[2]);
                break;
                case ', ':
                    $string->addFlag($match[2]);
                break;
            }
        }

        $this->poEditFile->addString($string);
    }

    private function parseMessage($part)
    {
        $keyParsed = false;
        $deprecated = false !== strpos($part, '#~ msgid');

        if (true === $deprecated) {
            $part = str_replace('#~ ', '', $part);
        }

        $lines = preg_split('#(\r\n|\n){1}#', substr($part, strpos($part, 'msgid')), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($lines as $line) {
            $line = trim($line);

            if (empty($line)) {
                continue;
            }

            if ('msgid "' === substr($line, 0, 7)) {
                $key = substr($line, 7, -1);
                continue;
            }

            if ('msgstr "' === substr($line, 0, 8)) {
                $keyParsed = true;
                $value = substr($line, 8, -1);
                continue;
            }

            if ('"' === $line[0]) {
                $val = substr($line, 1, -1);

                if (false === $keyParsed) {
                    $key .= $val;
                } else {
                    $value .= $val;
                }
            }
        }

        return new PoeditString(str_replace('\\"', '"', $key), str_replace('\\"', '"', $value), array(), array(), array(), array(), $deprecated);
    }
}
