<?php

namespace Jsgettext\Parser;

use Jsgettext\Poedit\PoeditString,
    Jsgettext\Poedit\PoeditFile;

class PoeditParser implements ParserInterface
{
    protected $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
    *   Parse a .po file and return a PoeditFile
    *   @return PoeditFile
    */
    public function parse()
    {
        $strings = array();
        $content = file_get_contents($this->file);
        $parts = preg_split('#(\r\n|\n){2}#', $content, -1, PREG_SPLIT_NO_EMPTY);

        if (count($parts) && false !== strpos($parts[0], 'msgid ""')) {
            $headers = array_shift($parts);
        } else {
            $headers = 'msgid ""' . "\n" . 'msgstr ""';
        }

        foreach ($parts as $part) {
            // parse comments
            $comments = array();
            preg_match_all('#^\\#: (.*?)$#m', $part, $matches, PREG_SET_ORDER);
            foreach ($matches as $m) {
                $comments[] = $m[1];
            }

            $isFuzzy = preg_match('#^\\#, fuzzy$#im', $part) ? true : false;
            preg_match_all('# ^ (msgid|msgstr)\ " ( (?: (?>[^"\\\\]++) | \\\\\\\\ | (?<!\\\\)\\\\(?!\\\\) | \\\\" )* ) (?<!\\\\)" $ #ixm', $part, $matches2, PREG_SET_ORDER);

            $k = stripslashes($matches2[0][2]);
            $v = !empty($matches2[1][2]) ? stripslashes($matches2[1][2]) : '';

            $strings[$k] = new PoeditString($k, $v, $isFuzzy, $comments);
        }

        return new PoeditFile($headers, $strings);
    }
}