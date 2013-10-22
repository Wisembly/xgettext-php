<?php

namespace Jsgettext\Tests\Parser;

use Jsgettext\Tests\TestCase,
    Jsgettext\Parser\PoeditParser;

class PoeditParserTest extends TestCase
{
    public function testParsePoeditFile()
    {
        $parser = new PoeditParser(__DIR__ . '/../Resources/test.po');
        $content = $parser->parse();

        $strings = $content->getStrings();
        $this->assertInstanceOf('\Jsgettext\Poedit\PoeditFile', $content);
        $this->assertCount(3, $strings);
        $this->assertCount(9, $content->getString('Edit')->getComments());
    }
}