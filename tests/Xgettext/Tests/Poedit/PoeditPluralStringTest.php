<?php

namespace Xgettext\Tests\Poedit;

use Xgettext\Tests\TestCase,
    Xgettext\Poedit\PoeditPluralString as String;

class PoeditPluralStringTest extends TestCase
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
        $string = new String('Reference text', 'Reference {{ count }} plural', array('Translated value in whatever languague you want', 'Same for plural'));
        $this->assertEquals($string->__toString(),<<<EOT
msgid "Reference text"
msgid_plural "Reference {{ count }} plural"
msgstr[0] "Translated value in whatever languague you want"
msgstr[1] "Same for plural"


EOT
        );
    }

    public function testStringWithDeprecated()
    {
        $string = new String('Reference text', 'Reference {{ count }} plural', array('Translated value in whatever languague you want', 'Same for plural'));
        $string->setDeprecated(true);
        $this->assertTrue($string->isDeprecated());
        $this->assertEquals($string->__toString(),<<<EOT
#~ msgid "Reference text"
#~ msgid_plural "Reference {{ count }} plural"
#~ msgstr[0] "Translated value in whatever languague you want"
#~ msgstr[1] "Same for plural"


EOT
        );
    }
}
