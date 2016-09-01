<?php

namespace Xgettext\Tests\Poedit;

use Xgettext\Tests\TestCase,
    Xgettext\Poedit\PoeditString;

class PoeditStringTest extends TestCase
{
    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidString()
    {
        new PoeditString('', 'value');
    }

    public function testSimpleString()
    {
        $string = new PoeditString('Reference text', 'Translated value in whatever languague you want');
        $this->assertEquals($string->__toString(),<<<EOT
msgid "Reference text"
msgstr "Translated value in whatever languague you want"


EOT
        );
    }

    public function testStringWFull()
    {
        $string = new PoeditString('Reference text', 'Translated value in whatever languague you want', array('translator comment', 'another one'), array('extracted comment'), array('../../first/file.js:85', '../../second/file.html:42'), array('fuzzy', 'foo'));
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
        $string = new PoeditString(
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
