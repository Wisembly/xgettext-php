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
        $string = new String('Reference text', 'Translated value in whatever languague you want', true);
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
        $string = new String('Reference text', 'Translated value in whatever languague you want', false, array('../../first/file.js:85', '../../second/file.html:42'));
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
        $string = new String('Reference text', 'Translated value in whatever languague you want', true, array('../../first/file.js:85', '../../second/file.html:42'));
        $this->assertEquals($string->__toString(),<<<EOT
#: ../../first/file.js:85
#: ../../second/file.html:42
#, fuzzy
msgid "Reference text"
msgstr "Translated value in whatever languague you want"


EOT
        );
    }

    public function testComments()
    {
        $string = new String('Reference text', 'Translated value in whatever languague you want', false, array('foo'));
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
    }
}