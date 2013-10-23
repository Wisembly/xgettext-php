<?php

namespace Jsgettext\Tests\Poedit;

use Jsgettext\Tests\TestCase,
    Jsgettext\Poedit\PoeditFile,
    Jsgettext\Poedit\PoeditString as String;

class PoeditFileTest extends TestCase
{
    public function testFile()
    {
        $file = new PoeditFile('my header', array(new String('key', 'value')));
        $this->assertInstanceOf('\Jsgettext\Poedit\PoeditFile', $file);
        $this->assertEquals('my header', $file->getHeaders());
        $file->setHeaders('my new header');
        $this->assertEquals('my new header', $file->getHeaders());
        $this->assertEquals(null, $file->getString('baz'));
    }

    public function testStrings()
    {
        $file = new PoeditFile();
        $file->addString(new String('foo', 'bar', false, array('comment1')));
        $file->addString(new String('bar', 'baz', false, array('comment1')));
        $this->assertCount(2, $file->getStrings());
        $this->assertCount(1, $file->getString('foo')->getComments());

        $file->addString(new String('foo', 'bar', false, array('comment2')));
        $this->assertCount(2, $file->getStrings());
        $this->assertCount(2, $file->getString('foo')->getComments());

        $file->removeString('bar');
        $file->removeString('notfound');
        $this->assertCount(1, $file->getStrings());
        $this->assertTrue($file->hasString('foo'));
        $this->assertFalse($file->hasString('bar'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testWrongConstruct()
    {
        $file = new PoeditFile(null, array('foo', 'bar'));
    }

    public function testGetters()
    {
        $file = new PoeditFile();
        $file->addString(new String('foo', 'bar', false, array('comment1')));
        $file->addString(new String('bar', 'baz', true, array('comment1')));
        $file->addString(new String('qux'));

        $untranslated = $file->getUntranslated();
        $fuzzy = $file->getFuzzy();
        $translated = $file->getTranslated();

        $this->assertCount(2, $translated);
        $this->assertCount(1, $fuzzy);
        $this->assertCount(1, $untranslated);
    }
}