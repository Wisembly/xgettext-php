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