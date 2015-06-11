<?php

namespace Xgettext\Poedit;

use \InvalidArgumentException;

class PoeditFile
{
    private $headers;
    private $strings;

    public $headersArray;

    public function __construct($headers = null, array $strings = array())
    {
        $this->strings = array();
        $this->setHeaders(null === $headers ? 'msgid ""' . PHP_EOL . 'msgstr ""' : $headers);

        foreach ($strings as $string) {
            if (!($string instanceof AbstractPoeditString)) {
                throw new InvalidArgumentException('You must give a PoeditStrings array');
            }

            $this->strings[$string->getKey()] = $string;
        }
    }

    public function getUntranslated()
    {
        $untranslated = array();

        foreach ($this->strings as $string) {
            if ($string->isEmpty()) {
                $untranslated[] = $string;
            }
        }

        return $untranslated;
    }

    public function getFuzzy()
    {
        $fuzzy = array();

        foreach ($this->strings as $string) {
            if ($string->isFuzzy()) {
                $fuzzy[] = $string;
            }
        }

        return $fuzzy;
    }

    public function getTranslated()
    {
        $translated = array();

        foreach ($this->strings as $string) {
            if (!$string->isEmpty() && !$string->isFuzzy()) {
                $translated[] = $string;
            }
        }

        return $translated;
    }

    public function getDeprecated()
    {
        $deprecated = array();

        foreach ($this->strings as $string) {
            if ($string->isDeprecated()) {
                $deprecated[] = $string;
            }
        }

        return $deprecated;
    }

    public function decodeHeaders($headers)
    {
        preg_match_all('/"(.*?):(.*?)"/mi', str_replace('\n', '', $headers), $matches);

        foreach ($matches[1] as $index => $header) {
            $this->setHeader(trim($header), trim($matches[2][$index]));
        }

        return $this;
    }

    // public function encodeHeaders(array $headers)
    // {
    //     return $this;
    // }

    public function setHeader($header, $value)
    {
        $this->headersArray[$header] = $value;

        return $this;
    }

    public function getHeader($header, $placeholder = null)
    {
        return isset($this->headersArray[$header]) ? $this->headersArray[$header] : $placeholder;
    }

    public function setHeaders($headers)
    {
        $this->headers = $headers;
        $this->decodeHeaders($headers);

        return $this;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function addHeader($header)
    {
        $this->decodeHeaders($header);
        $this->headers .= $header . PHP_EOL;

        return $this;
    }

    public function getLang()
    {
        return $this->getHeader('Language');
    }

    public function getPluralForms()
    {
        return $this->getHeader('Plural-Forms');
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

    public function addString(AbstractPoeditString $string)
    {
        $key = $string->getKey();

        if (!$this->hasString($key)) {
            $this->strings[$key] = $string;
        } else {
            if ($this->getString($key)->isEqualTo($string)) {
                $this->strings[$key]
                    ->addComments($string->getComments())
                    ->addExtracteds($string->getExtracteds())
                    ->addReferences($string->getReferences())
                    ->addFlags($string->getFlags());
            } else {
                $this->strings[$key] = $string;
            }
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
        uasort($this->strings, function ($a, $b) {
           return $a->getKey() < $b->getKey() ? -1 : 1;
        });

        return $this;
    }
}
