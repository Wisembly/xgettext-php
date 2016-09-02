<?php

namespace Xgettext\Tests\Parser;

use Xgettext\Tests\TestCase,
    Xgettext\Parser\JavascriptParser;

class JavascriptParserTest extends TestCase
{
    public function testParseTestJsFile()
    {
        $parser = new JavascriptParser(__DIR__ . '/../Resources/test.js', array('__'));
        $content = $parser->parse();

        $this->assertCount(12, $content);
        $this->assertInstanceOf('\Xgettext\Poedit\PoeditString', $content['Hello world, testing xgettext']);
        $references = $content['Test string']->getReferences();
        $this->assertEquals('test.js:6', substr($references[0], -9));

        $this->assertInstanceOf('\Xgettext\Poedit\PoeditString', $content["A <span class='someclass'>{{complex}}</span> string with <span class='anotherclass'>{{variables}}</span>."]);
    }

    public function testParseTestJsFileOnlyPlurals()
    {
        $parser = new JavascriptParser(__DIR__ . '/../Resources/test.js', array('_n:1,2'));
        $content = $parser->parse();

        $this->assertCount(3, $content);
        $this->assertInstanceOf('\Xgettext\Poedit\PoeditPluralString', $content['singular text']);
        $this->assertEquals('{{ count }} plural', $content['singular text']->getPluralForm());
        $this->assertEquals('another {{ count }} plural', $content['singular text again']->getPluralForm());
    }

    public function testParseTestJsFileWithPlurals()
    {
        $parser = new JavascriptParser(__DIR__ . '/../Resources/test.js', array('__', '_n:1,2'));
        $content = $parser->parse();

        $this->assertCount(15, $content);
    }

    public function testParseTestHtmlFile()
    {
        $parser = new JavascriptParser(__DIR__ . '/../Resources/test.html', array('__'));
        $content = $parser->parse();

        $this->assertCount(6, $content);
        $this->assertInstanceOf('\Xgettext\Poedit\PoeditString', $content['hello %placeholder%']);
        $string = $content['test string title'];
        $this->assertCount(2, $string->getReferences());
    }
}
