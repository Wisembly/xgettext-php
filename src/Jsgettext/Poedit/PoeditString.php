<?php

namespace Jsgettext\PoEdit;

use \InvalidArgumentException;

class PoeditString
{
    private $key;
    private $value;
    private $comments;
    private $deprecated;

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
        foreach ($this->comments['comments'] as $comment) {
            $string .= "#  {$comment}" . PHP_EOL;
        }

        foreach ($this->comments['extracteds'] as $extracted) {
            $string .= "#. {$extracted}" . PHP_EOL;
        }

        foreach ($this->comments['references'] as $reference) {
            $string .= "#: {$reference}" . PHP_EOL;
        }

        foreach ($this->comments['flags'] as $flag) {
            $string .= "#, {$flag}" . PHP_EOL;
        }

        $string .= ($this->isDeprecated() ? '#~ ' : '') . 'msgid "' . str_replace('"', '\\"', $this->key) . '"' . PHP_EOL;
        $string .= ($this->isDeprecated() ? '#~ ' : '') . 'msgstr "' . str_replace('"', '\\"', $this->value) . '"' . PHP_EOL;
        $string .= PHP_EOL;

        return $string;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function isEmpty()
    {
        return empty($this->value);
    }

    public function isFuzzy()
    {
        return $this->hasFlag('fuzzy');
    }

    public function setFuzzy($fuzzy)
    {
        if (true === $fuzzy) {
            $this->addFlag('fuzzy');
        } else {
            $this->removeFlag('fuzzy');
        }

        return $this;
    }

    public function isDeprecated()
    {
        return true === $this->deprecated;
    }

    public function setDeprecated($deprecated)
    {
        $this->deprecated = $deprecated;
    }

    public function getReferences()
    {
        return $this->comments['references'];
    }

    public function addReference($reference)
    {
        return $this->_addComment('references', $reference);
    }

    public function addReferences(array $references)
    {
        return $this->_addComments('references', $references);
    }

    public function removeReference($reference)
    {
        return $this->_removeComment('references');
    }

    public function sortReferences()
    {
        return $this->_sortComments('references');
    }

    public function hasReference($reference)
    {
        return $this->_hasComment('references', $reference);
    }

    public function getComments()
    {
        return $this->comments['comments'];
    }

    public function addComment($comment)
    {
        return $this->_addComment('comments', $comment);
    }

    public function addComments(array $comments)
    {
        return $this->_addComments('comments', $comments);
    }

    public function removeComment($comment)
    {
        return $this->_removeComment('comments', $comment);
    }

    public function sortComments()
    {
        return $this->_sortComments('comments');
    }

    public function hasComment($comment)
    {
        return $this->_hasComment('comments', $comment);
    }

    public function getFlags()
    {
        return $this->$flags['flags'];
    }

    public function addFlag($flag)
    {
        return $this->_addComment('flags', $flag);
    }

    public function addFlags(array $flags)
    {
        return $this->_addComments('flags', $flags);
    }

    public function removeFlag($flag)
    {
        return $this->_removeComment('flags', $flag);
    }

    public function hasFlag($flag)
    {
        return $this->_hasComment('flags', $flag);
    }

    public function sortFlags()
    {
        return $this->_sortComments('flags');
    }

    public function getExtracteds()
    {
        return $this->comments['extracteds'];
    }

    public function addExtracted($extracted)
    {
        return $this->_addComment('extracteds', $extracted);
    }

    public function addExtracteds(array $extracteds)
    {
        return $this->_addComments('extracteds', $extracteds);
    }

    public function removeExtracted($extracted)
    {
        return $this->_removeComment('extracteds');
    }

    public function sortExtracteds()
    {
        return $this->_sortComments('extracteds');
    }

    public function hasExtracted($extracted)
    {
        return $this->_hasComment('extracteds', $extracted);
    }

    public function _getComments($key = 'comments')
    {
        return $this->comments[$key];
    }

    public function _addComment($key, $comment)
    {
        $this->comments[$key][] = $comment;
        $this->comments[$key] = array_unique($this->comments[$key]);

        return $this;
    }

    public function _addComments($key, array $comments)
    {
        $this->comments[$key] = array_unique(array_merge($this->comments[$key], $comments));

        return $this;
    }

    public function _removeComment($key, $comment)
    {
        foreach ($this->comments[$key] as $k => $value) {
            if ($comment === $value) {
                unset($this->comments[$key][$k]);
                break;
            }
        }

        return $this;
    }

    public function _sortComments($key = 'comments')
    {
        sort($this->comments[$key]);

        return $this;
    }

    public function _hasComment($key, $comment)
    {
        foreach ($this->comments[$key] as $k => $value) {
            if ($value === $comment) {
                return true;
            }
        }

        return false;
    }
}