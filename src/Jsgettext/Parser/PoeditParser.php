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
            $headers = 'msgid ""' . PHP_EOL . 'msgstr ""';
        }

        foreach ($parts as $part) {
            // parse comments
            $comments = array();
            preg_match_all('#^\\#: (.*?)$#m', $part, $matches, PREG_SET_ORDER);
            foreach ($matches as $m) {
                $comments[] = $m[1];
            }

            preg_match_all('# ^ (msgid|msgstr)\ " ( (?: (?>[^"\\\\]++) | \\\\\\\\ | (?<!\\\\)\\\\(?!\\\\) | \\\\" )* ) (?<!\\\\)" $ #ixm', $part, $matches, PREG_SET_ORDER);

            if (empty($matches)) {
                continue;
            }

            $key = stripslashes($matches[0][2]);

            if (empty($key)) {
                continue;
            }

            $strings[$key] = new PoeditString($key, !empty($matches[1][2]) ? stripslashes($matches[1][2]) : '', !!preg_match('#^\\#, fuzzy$#im', $part), $comments);
        }

        return new PoeditFile($headers, $strings);
    }
}