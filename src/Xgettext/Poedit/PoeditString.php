<?php

namespace Xgettext\Poedit;

use \InvalidArgumentException;

class PoeditString extends AbstractPoeditString
{
    function __construct($key, $value = '', array $comments = array(), array $extracted = array(), array $references = array(), array $flags = array(), $deprecated = false)
    {
        if (empty($key)) {
            throw new InvalidArgumentException('PoeditString key could not be empty');
        }

        $this->key = $key;
        $this->value = $value;
        $this->deprecated = $deprecated;

        $this->comments = array(
            'references' => $references,
            'comments'   => $comments,
            'extracteds' => $extracted,
            'flags'      => $flags,
        );
    }

    public function __toString()
    {
        $string = '';
        $string .= $this->dumpComments();
        $string .= $this->dumpString(($this->isDeprecated() ? '#~ ' : '') . 'msgid "', $this->key, '"' . PHP_EOL);
        $string .= $this->dumpString(($this->isDeprecated() ? '#~ ' : '') . 'msgstr "', $this->value, '"' . PHP_EOL);
        $string .= PHP_EOL;

        return $string;
    }

    public function isEmpty()
    {
        return empty($this->value);
    }
}
