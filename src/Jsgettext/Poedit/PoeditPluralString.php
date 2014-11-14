<?php

namespace Jsgettext\Poedit;

use \InvalidArgumentException;

class PoeditPluralString extends AbstractPoeditString
{
    function __construct($key, $pluralForm, array $plurals = array(), array $comments = array(), array $extracted = array(), array $references = array(), array $flags = array(), $deprecated = false)
    {
        if (empty($key) || empty($pluralForm)) {
            throw new InvalidArgumentException('PoeditString key or pluralForm could not be empty');
        }

        $this->key = $key;
        $this->pluralForm = $pluralForm;

        $this->plurals = $plurals;
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
        $string .= $this->dumpString(($this->isDeprecated() ? '#~ ' : '') . 'msgid_plural "', $this->pluralForm, '"' . PHP_EOL);

        $string .= $this->dumpPlurals();

        $string .= PHP_EOL;

        return $string;
    }

    public function isEmpty()
    {
        return empty($this->plurals);
    }

    public function getValue()
    {
        throw new InvalidArgumentException('Could not set the a value of a PoeditPluralString', 1);
    }

    public function setValue($value)
    {
        throw new InvalidArgumentException('Could not set a value to a PoeditPluralString', 1);
    }

    public function getPluralForm()
    {
        return $this->pluralForm;
    }

    public function setPluralForm($pluralForm)
    {
        $this->pluralForm = $pluralForm;

        return $this;
    }

    public function getPlurals()
    {
        return $this->plurals;
    }

    public function getPlural($index)
    {
        if (!isset($this->plurals[$index])) {
            throw new InvalidArgumentException(sprintf('The index %s is not found', $index), 1);
        }

        return $this->plurals[$index];
    }

    public function setPlurals(array $plurals)
    {
        $this->plurals = $plurals;

        return $this;
    }

    private function dumpPlurals()
    {
        if (empty($this->plurals)) {
            return $this->dumpString(($this->isDeprecated() ? '#~ ' : '') . 'msgstr[0] ""' . PHP_EOL);
        }

        $string = '';

        foreach ($this->plurals as $index => $translation) {
            $string .= $this->dumpString(($this->isDeprecated() ? '#~ ' : '') . 'msgstr[' , $index , '] "' . $translation . '"' . PHP_EOL);
        }

        return $string;
    }
}
