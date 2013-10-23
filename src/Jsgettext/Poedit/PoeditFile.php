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
        $this->headers = null === $headers ? 'msgid ""' . PHP_EOL . 'msgstr ""' : $headers;

        foreach ($strings as $string) {
            if (!($string instanceof PoeditString)) {
                throw new InvalidArgumentException('You must give a PoeditStrings array');
            }

            $this->strings[$string->getKey()] = $string;
        }
    }

    public function setHeaders($headers)
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
            $this->strings[$key]->addComments($string->getComments());
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

    public function removeString($key)
    {
        if (!$this->hasString($key)) {
            return $this;
        }

        unset($this->strings[$key]);

        return $this;
    }

    public function sortStrings()
    {
        asort($this->strings);

        return $this;
    }
}