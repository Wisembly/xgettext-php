<?php

namespace Xgettext\Parser;

use \InvalidArgumentException;

use Xgettext\Poedit\PoeditString,
    Xgettext\Poedit\PoeditPluralString,
    Xgettext\Poedit\PoeditFile;

class PoeditParser
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

    public function setPatterns()
    {}

    /**
    *   Parse a .po file and return a PoeditFile
    *   @return PoeditFile
    */
    public function parse($string = null)
    {
        $strings = array();
        $content = null !== $string ? $string : file_get_contents($this->file);

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

    // TODO: refacto
    private function parseMessage($part)
    {
        $step = null;
        $key = null;
        $value = null;
        $pluralForm = null;
        $plurals = array();
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
                $step = 'msgid';
                $key = substr($line, 7, -1);
                continue;
            }

            if ('msgstr "' === substr($line, 0, 8)) {
                $step = 'msgstr';
                $value = substr($line, 8, -1);
                continue;
            }

            if ('msgid_plural "' === substr($line, 0, 14)) {
                $step = 'msgid_plural';
                $pluralForm = substr($line, 14, -1);
                continue;
            }

            if ('msgstr[' === substr($line, 0, 7)) {
                $step = 'msgstr[';
                $index = (int) substr($line, 7, 1);
                $plural = substr($line, 11, -1);
                $plurals[$index] = $plural;
                continue;
            }

            if ('"' === $line[0]) {
                $val = substr($line, 1, -1);

                switch ($step) {
                    case 'msgid':
                        $key .= $val;
                        break;
                    case 'msgstr':
                        $value .= $val;
                        break;
                    case 'msgid_plural':
                        $pluralForm .= $val;
                        break;
                    case 'msgstr[':
                        $plurals[$index] .= $val;
                        break;
                    default:
                        throw new InvalidArgumentException('Parsing error', 1);
                }
            }
        }

        if (null !== $pluralForm) {
            $plurals = array_map(function ($plural) {
                return str_replace('\\"', '"', $plural);
            }, $plurals);

            return new PoeditPluralString(str_replace('\\"', '"', $key), str_replace('\\"', '"', $pluralForm), $plurals, array(), array(), array(), array(), $deprecated);
        } else {
            return new PoeditString(str_replace('\\"', '"', $key), str_replace('\\"', '"', $value), array(), array(), array(), array(), $deprecated);
        }
    }
}
