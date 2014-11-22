# Xgettext

[![Build Status](https://travis-ci.org/Wisembly/xgettext.png?branch=master)](https://travis-ci.org/Wisembly/xgettext)

Parser for poEdit implemented in PHP. use same API as xgettext. We use it for parsing our
javascript and html templates files in our Backbone apps.
Caution: this is **NOT** an xgettext javascript language implementation, this is **JUST**
a parser for many files (that we use for our *.js and *.html files) and have no language
syntax check implementation.

Heavily inspired by [xgettext](https://code.google.com/p/xgettext/)


# Install

The recommended way to install Xgettext is through
[Composer](http://getcomposer.org/):

``` json
{
    "require": {
        "wisembly/xgettext": "@stable"
    }
}
```


# Usage

## Parser

Simply create a new parser in poEdit (name it for example `Javascript`)`.
Then, set up xgettext like this:

![configure xgettext parser](https://github.com/Wisembly/xgettext/raw/master/doc/screen.png)

## JSON Dumper

```
$ bin/dumper -i input.po -o output.json
```

## Requirements

PHP >= 5.3


## Licence

Xgettext is released under the MIT License. See the bundled LICENSE file for details.
