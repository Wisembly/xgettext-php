# Jsgettext

[![Build Status](https://travis-ci.org/Wisembly/jsgettext.png?branch=master)](https://travis-ci.org/Wisembly/jsgettext)

Parser for poEdit implemented in PHP. use same API as xgettext. We use it for parsing our
javascript and html templates files in our Backbone apps.
Caution: this is **NOT** an xgettext javascript language implementation, this is **JUST**
a parser for many files (that we use for our *.js and *.html files) and have no language
syntax check implementation.

Heavily inspired by [jsgettext](https://code.google.com/p/jsgettext/)


# Install

The recommended way to install Jsgettext is through
[Composer](http://getcomposer.org/):

``` json
{
    "require": {
        "wisembly/jsgettext": "@stable"
    }
}
```


# Usage

## Parser

Simply create a new parser in poEdit (name it for example `Javascript`)`.
Then, set up jsgettext like this:

![configure jsgettext parser](https://github.com/Wisembly/jsgettext/raw/master/doc/screen.png)

## JSON Dumper

```
$ bin/dumper -i input.po -o output.json
```

## Requirements

PHP >= 5.3


## Licence

Jsgettext is released under the MIT License. See the bundled LICENSE file for details.
