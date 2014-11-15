<?php

namespace Jsgettext\Tests\Poedit;

use Jsgettext\Tests\TestCase,
    Jsgettext\Poedit\PoeditString as String;

class PoeditStringTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidString()
    {
        new String('', 'value');
    }

    public function testSimpleString()
    {
        $string = new String('Reference text', 'Translated value in whatever languague you want');
        $this->assertEquals($string->__toString(),<<<EOT
msgid "Reference text"
msgstr "Translated value in whatever languague you want"


EOT
        );
    }

    public function testStringWFull()
    {
        $string = new String('Reference text', 'Translated value in whatever languague you want', array('translator comment', 'another one'), array('extracted comment'), array('../../first/file.js:85', '../../second/file.html:42'), array('fuzzy', 'foo'));
        $this->assertEquals($string->__toString(),<<<EOT
#  translator comment
#  another one
#. extracted comment
#: ../../first/file.js:85
#: ../../second/file.html:42
#, fuzzy
#, foo
msgid "Reference text"
msgstr "Translated value in whatever languague you want"


EOT
        );
    }

    public function testMultilines()
    {
        $string = new String(
            'This is a very long string, to test how PoeditDumper will dump it, using multiline syntax. It should be displayed on three different lines, with 78 chars maximum each.', '');
        $this->assertEquals($string->__toString(),<<<EOT
msgid ""
"This is a very long string, to test how PoeditDumper will dump it, using "
"multiline syntax. It should be displayed on three different lines, with 78 "
"chars maximum each."
msgstr ""


EOT
        );
    }
}
