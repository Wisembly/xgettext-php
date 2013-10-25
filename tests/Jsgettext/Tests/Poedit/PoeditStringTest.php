<?php

namespace Jsgettext\Tests\Poedit;

use Jsgettext\Tests\TestCase,
    Jsgettext\Poedit\PoeditString as String;

class PoeditStringTest extends TestCase
{

    public function testString()
    {
        $string = new String('foo', 'bar');
        $this->assertEquals('bar', $string->getValue());
        $string->setValue('baz');
        $this->assertEquals('baz', $string->getValue());
        $string->setValue('');
        $this->assertTrue($string->isEmpty());
        $this->assertFalse($string->isFuzzy());
        $string->setFuzzy(true);
        $this->assertTrue($string->isFuzzy());
    }

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

    public function testStringWithFuzzy()
    {
        $string = new String('Reference text', 'Translated value in whatever languague you want');
        $string->setFuzzy(true);
        $this->assertTrue($string->isFuzzy());
        $this->assertEquals($string->__toString(),<<<EOT
#, fuzzy
msgid "Reference text"
msgstr "Translated value in whatever languague you want"


EOT
        );
    }

    public function testStringWithComments()
    {
        $string = new String('Reference text', 'Translated value in whatever languague you want', array(), array(), array('../../first/file.js:85', '../../second/file.html:42'));
        $this->assertEquals($string->__toString(),<<<EOT
#: ../../first/file.js:85
#: ../../second/file.html:42
msgid "Reference text"
msgstr "Translated value in whatever languague you want"


EOT
        );
    }

    public function testStringWithCommentsAndFuzzy()
    {
        $string = new String('Reference text', 'Translated value in whatever languague you want', array(), array(), array('../../first/file.js:85', '../../second/file.html:42'), array('fuzzy'));
        $this->assertEquals($string->__toString(),<<<EOT
#: ../../first/file.js:85
#: ../../second/file.html:42
#, fuzzy
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

    public function testComments()
    {
        $string = new String('Reference text', 'Translated value in whatever languague you want', array('foo'));
        $this->assertEquals($string->getComments(), array('foo'));

        $string->addComment('bar');
        $this->assertEquals($string->getComments(), array('foo', 'bar'));

        $string->addComment('bar');
        $this->assertEquals($string->getComments(), array('foo', 'bar'));

        $string->removeComment('foo');
        $comments = $string->getComments();
        $this->assertContains('bar', $comments);
        $this->assertFalse(isset($comments['foo']));

        $string->addComments(array('foo', 'bar', 'baz'));
        $comments = $string->getComments();
        $this->assertContains('foo', $comments);
        $this->assertContains('bar', $comments);
        $this->assertContains('baz', $comments);

        $string->removeFlag('fuzzy');
        $this->assertFalse($string->isFuzzy());

        $string->setFuzzy(true);
        $this->assertTrue($string->isFuzzy());

        $string = new String('foo', 'bar', array('foo'), array('bar'), array('baz'), array('qux'));
        $string->addComment('bar');
        $string->addExtracted('baz');
        $string->addReference('qux');
        $string->addFlag('fuzzy');

        // accessors
        $this->assertTrue($string->isFuzzy());
        $this->assertTrue($string->hasComment('foo'));
        $this->assertTrue($string->hasExtracted('bar'));
        $this->assertTrue($string->hasReference('baz'));
        $this->assertTrue($string->hasFlag('qux'));
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