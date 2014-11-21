<?php

namespace Jsgettext\Tests\Parser;

use Jsgettext\Tests\TestCase,
    Jsgettext\Parser\JavascriptParser;

class JavascriptParserTest extends TestCase
{
    public function testParseTestJsFile()
    {
        $parser = new JavascriptParser(__DIR__ . '/../Resources/test.js', array('__'));
        $content = $parser->parse();

        $this->assertCount(9, $content);
        $this->assertInstanceOf('\Jsgettext\Poedit\PoeditString', $content['Hello world, testing jsgettext']);
        $this->assertEquals('test.js:6', substr($content['Test string']->getReferences()[0], -9));
    }

    public function testParseTestJsFileOnlyPlurals()
    {
        $parser = new JavascriptParser(__DIR__ . '/../Resources/test.js', array('_n:1,2'));
        $content = $parser->parse();

        $this->assertCount(2, $content);
        $this->assertInstanceOf('\Jsgettext\Poedit\PoeditPluralString', $content['singular text']);
        $this->assertEquals('{{ count}} plural', $content['singular text']->getPluralForm());
        $this->assertEquals('another {{ count}} plural', $content['singular text again']->getPluralForm());
    }

    public function testParseTestJsFileWithPlurals()
    {
        $parser = new JavascriptParser(__DIR__ . '/../Resources/test.js', array('__', '_n:1,2'));
        $content = $parser->parse();

        $this->assertCount(11, $content);
    }

    public function testParseTestHtmlFile()
    {
        $parser = new JavascriptParser(__DIR__ . '/../Resources/test.html', array('__'));
        $content = $parser->parse();

        $this->assertCount(6, $content);
        $this->assertInstanceOf('\Jsgettext\Poedit\PoeditString', $content['hello %placeholder%']);
        $string = $content['test string title'];
        $this->assertCount(2, $string->getReferences());
    }
}
