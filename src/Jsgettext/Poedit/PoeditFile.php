<?php

namespace Jsgettext\PoEdit;

use \InvalidArgumentException;

class PoeditFile
{
    private $headers;
    private $strings;

    public function __construct($headers = null, array $strings = array())
    {
        $this->strings = array();
        $this->headers = null === $headers ? 'msgid ""' . "\n" . 'msgstr ""' : $headers;

        foreach ($strings as $key => $string) {
            if (!($string instanceof PoeditString)) {
                throw new InvalidArgumentException('You must give a PoeditStrings array');
            }

            $this->strings[$string->getKey()] = $string;
        }
    }

    public function setHeaders()
    {
        $this->headers = $headers;

        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getStrings()
    {
        return array_values($this->strings);
    }

    public function getString($key)
    {
        if (!$this->hasString($key)) {
            return null;
        }

        return $this->strings[$key];
    }

    public function hasString($key)
    {
        return isset($this->strings[$key]);
    }

    public function addString(PoeditString $string)
    {
        $key = $string->getKey();

        if (!$this->hasString($key)) {
            $this->strings[$key] = $string;
        } else {
            $this->strings[$key]->addComments($string->comments);
        }

        return $this;
    }

    public function addStrings(array $strings)
    {
        foreach ($strings as $string) {
            $this->addString($string);
        }

        return $this;
    }

    public function removeString(PoeditString $string)
    {
        if (!$this->hasString($string->getKey())) {
            return $this;
        }

        unset($this->strings[$string->getKey()]);

        return $this;
    }
}