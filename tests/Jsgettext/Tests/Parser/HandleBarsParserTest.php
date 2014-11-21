<?php

namespace Jsgettext\Tests\Parser;

use Jsgettext\Tests\TestCase,
    Jsgettext\Parser\HandleBarsParser;

class JavascriptParserTest extends TestCase
{
    public function testParseTestJsFile()
    {
        $parser = new HandleBarsParser(__DIR__ . '/../Resources/test.hbs', array('_t', '_n:1,2'));
        $content = $parser->parse();

        $this->assertCount(2, $content);
        $this->assertInstanceOf('\Jsgettext\Poedit\PoeditString', $content['Hello {{ username }}!']);
        $references = $content['Hello {{ username }}!']->getReferences();
        $this->assertEquals('test.hbs:3', substr($references[0], -10));

        $this->assertInstanceOf('\Jsgettext\Poedit\PoeditPluralString', $content['Singluar {{ count }}']);
        $this->assertEquals('Plural {{ count }}', $content['Singluar {{ count }}']->getPluralForm());
    }
}
