<?php

namespace Xgettext\Parser;

use Xgettext\Poedit\PoeditString,
    Xgettext\Poedit\PoeditPluralString;

abstract class AbstractRegexParser
{
    protected $file;
    protected $keywords;
    protected $strings;

    public function __construct($file, array $keywords = array('_'))
    {
        $this->file = $file;
        $this->strings = array();
        $this->keywords = $this->handleKeywords($keywords);
    }

    // make keword list and argument positions
    private function handleKeywords($keywords)
    {
        $kwds = array();

        foreach ($keywords as $keyword) {
            if (false !== ($pos = strpos($keyword, ':'))) {
                preg_match_all('`([\d]+)`', $keyword, $matches);

                $kwds[substr($keyword, 0, $pos)] = $matches[0];
                continue;
           }

           $kwds[$keyword] = array(1);
        }

        return $kwds;
    }

    public function extractCalls($line)
    {
        return array();
    }

    public function extractArguments($arguments)
    {
        return array();
    }

    public function parse($string = null)
    {
        $line_count = 0;
        $handle = fopen($this->file, "r");

        // foreach file line by line
        while ($handle && !feof($handle)) {
            $line = fgets($handle);
            $comment = $this->file . ':' . ++$line_count;

            $calls = $this->extractCalls($line);

            // nothing found in the parsed line
            if (empty($calls)) {
                continue;
            }

            // foreach every call match to analyze arguments, they must be strings
            foreach ($calls as $call) {
                $arguments = $this->extractArguments($call['arguments']);

                // false positive, no matching arguments inside
                if (empty($arguments)) {
                    continue;
                }

                // first argument is msgid
                $msgid = str_replace('\\' . $arguments[0]['delimiter'], $arguments[0]['delimiter'], $arguments[0]['arguments']);

                // if we did not have found already this string, create it
                if (!in_array($msgid, array_keys($this->strings))) {
                    // we have a plural form case
                    if (2 === count($this->keywords[$call['keyword']])) {
                        // we asked for a plural keyword above, but only one argument were found. Abort silently
                        if (!isset($arguments[1])) {
                            continue;
                        }

                        $msgid_plural = str_replace('\\' . $arguments[1]['delimiter'], $arguments[1]['delimiter'], $arguments[$this->keywords[$call['keyword']][1] - 1]['arguments']);
                        $this->strings[$msgid] = new PoeditPluralString($msgid, $msgid_plural);
                    } else {
                        $this->strings[$msgid] = new PoeditString($msgid);
                    }
                }

                // add line reference to newly created or already existing string
                $this->strings[$msgid]->addReference($comment);
            }
        }

        fclose($handle);

        return $this->strings;
    }
}
