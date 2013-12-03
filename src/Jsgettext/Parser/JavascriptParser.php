<?php

namespace Jsgettext\Parser;

use Jsgettext\Poedit\PoeditString;

class JavascriptParser implements ParserInterface
{
    private $file;
    private $keywords;
    private $strings;

    public function __construct($file, array $keywords = array('_'))
    {
        $this->file = $file;
        $this->keywords = $keywords;
        $this->strings = array();
    }

    public function parse()
    {
        $line_count = 1;
        $keywords = implode('|', $this->keywords);
        $handle = fopen($this->file, "r");

        while ($handle && !feof($handle)) {
            $line = fgets($handle);
            preg_match_all('`(?:' . $keywords . ')\(\s*([\'"])(.+?)(?=(?<!\\\)\1)\1`', $line, $matches);
            $comment = $this->file . ':' . $line_count;

            foreach ($matches[2] as $key => $str) {
                $delimiter = $matches[1][$key];
                $str = str_replace("\\$delimiter", $delimiter, $str);

                if (!in_array($str, array_keys($this->strings))) {
                    $this->strings[$str] = new PoeditString($str, '');
                }

                $this->strings[$str]->addReference($comment);
            }

            $line_count++;
        }

        fclose($handle);

        return $this->strings;
    }
}