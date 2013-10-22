<?php

namespace Jsgettext\PoEdit;

class PoeditString
{
    private $key;
    private $value;
    private $fuzzy;
    private $comments;

    function __construct($key, $value = '', $fuzzy = false, array $comments = array())
    {
        $this->key = $key;
        $this->value = $value;
        $this->fuzzy = $fuzzy;
        $this->comments = $comments;
    }

    public function __toString()
    {
        $string = '';
        foreach ($this->comments as $comment) {
            $string .= "#: $comment\n";
        }

        if (true === $this->fuzzy) {
            $string .= "#, fuzzy\n";
        }

        $string .= 'msgid "'.str_replace('"', '\\"', $this->key).'"' . "\n";
        $string .= 'msgstr "'.str_replace('"', '\\"', $this->value).'"' . "\n";
        $string .= "\n";

        return $string;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function isEmpty()
    {
        return empty($this->value);
    }

    public function setValue()
    {
        $this->value = $value;

        return $this;
    }

    public function isFuzzy()
    {
        return $this->fuzzy;
    }

    public function setFuzzy($fuzzy)
    {
        $this->fuzzy = (bool) $fuzzy;

        return $this;
    }

    public function getComments()
    {
        return $this->comments;
    }

    public function setComments(array $comments)
    {
        $this->comments = (array) $comments;
    }

    public function addComment($comment)
    {
        $this->comments[] = $comment;
        $this->comments = array_unique($this->comments);

        return $this;
    }

    public function addComments(array $comments)
    {
        $this->comments = array_unique(array_merge($this->comments, $comments));

        return $this;
    }

    public function removeComment($comment)
    {
        foreach ($this->comments as $key => $value) {
            if ($comment === $value) {
                unset($this->comments[$key]);
                break;
            }
        }

        return $this;
    }
}