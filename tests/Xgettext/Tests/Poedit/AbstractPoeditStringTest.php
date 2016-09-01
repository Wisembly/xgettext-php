<?php

namespace Xgettext\Tests\Poedit;

use Xgettext\Tests\TestCase,
    Xgettext\Poedit\PoeditString;

class AbstractPoeditStringTest extends TestCase
{
    public function testStringWithFuzzy()
    {
        $string = new PoeditString('Reference text', 'Translated value in whatever languague you want');
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
        $string = new PoeditString('Reference text', 'Translated value in whatever languague you want', array(), array(), array('../../first/file.js:85', '../../second/file.html:42'));
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
        $string = new PoeditString('Reference text', 'Translated value in whatever languague you want', array(), array(), array('../../first/file.js:85', '../../second/file.html:42'), array('fuzzy'));
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
        $string = new PoeditString('Reference text', 'Translated value in whatever languague you want', array('foo'));
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

        $string = new PoeditString('foo', 'bar', array('foo'), array('bar'), array('baz'), array('qux'));
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
}
