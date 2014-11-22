# Xgettext

[![Build Status](https://travis-ci.org/Wisembly/xgettext-php.png?branch=master)](https://travis-ci.org/Wisembly/xgettext-php)

xgettext PHP implementation. Curently provides 2 parsers:
- Javascript parser
- Handlebars parser


# Install

The recommended way to install Xgettext is through
[Composer](http://getcomposer.org/):

``` json
{
    "require": {
        "wisembly/xgettext-php": "@stable"
    }
}
```


# Usage


## Parser

### Javascript parser

Simply create a new parser in Poedit (name it for example `Javascript`)`.
Then, set up xgettext like this:

![configure xgettext parser](https://github.com/Wisembly/xgettext/raw/master/doc/js.png)

### Handlebars parser

Same like above, just add `-l "handlebars"` in yout `xgettext` call to stipulate you want
to use handlebars parser with xgettext-php.

![configure xgettext parser](https://github.com/Wisembly/xgettext/raw/master/doc/hbs.png)


## JSON Dumper

```
$ bin/dumper -i input.po -o output.json
```


## Requirements

PHP >= 5.3


## Licence

Xgettext is released under the MIT License. See the bundled LICENSE file for details.
