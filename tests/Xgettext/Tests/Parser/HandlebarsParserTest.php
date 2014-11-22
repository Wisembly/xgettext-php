<?php

namespace Xgettext\Tests\Parser;

use Xgettext\Tests\TestCase,
    Xgettext\Parser\HandlebarsParser;

class HandlebarsParserTest extends TestCase
{
    public function testParseTestJsFile()
    {
        $parser = new HandleBarsParser(__DIR__ . '/../Resources/test.hbs', array('_t', '_n:1,2'));
        $content = $parser->parse();

        $this->assertCount(2, $content);
        $this->assertInstanceOf('\Xgettext\Poedit\PoeditString', $content['Hello {{ username }}!']);
        $references = $content['Hello {{ username }}!']->getReferences();
        $this->assertEquals('test.hbs:3', substr($references[0], -10));

        $this->assertInstanceOf('\Xgettext\Poedit\PoeditPluralString', $content['Singluar {{ count }}']);
        $this->assertEquals('Plural {{ count }}', $content['Singluar {{ count }}']->getPluralForm());
    }
}
