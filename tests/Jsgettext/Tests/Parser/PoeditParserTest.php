<?php

namespace Jsgettext\Tests\Parser;

use Jsgettext\Tests\TestCase,
    Jsgettext\Parser\PoeditParser;

class PoeditParserTest extends TestCase
{
    public function testParseSimplePoeditFile()
    {
        $parser = new PoeditParser(__DIR__ . '/../Resources/simple.po');
        $content = $parser->parse();

        $strings = $content->getStrings();
        $this->assertInstanceOf('\Jsgettext\Poedit\PoeditFile', $content);
        $this->assertCount(3, $strings);
        $this->assertCount(0, $content->getString('Edit')->getComments());
    }

    public function testParsePoeditFile()
    {
        $parser = new PoeditParser(__DIR__ . '/../Resources/full.po');
        $file = $parser->parse();

        $strings = $file->getStrings();
        $this->assertInstanceOf('\Jsgettext\Poedit\PoeditFile', $file);
        $this->assertCount(3, $strings);
        $this->assertCount(9, $file->getString('Edit')->getComments());

        $this->assertTrue($file->hasString('Download \\\'escaped simple quotes\\\''));
        $this->assertTrue($file->hasString('Preview "with double quotes"'));
    }
}