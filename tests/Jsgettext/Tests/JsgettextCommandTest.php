<?php

namespace Jsgettext\Tests;

use Jsgettext\Tests\TestCase,
    Jsgettext\Parser\PoeditParser;

class JsgettextCommandTest extends TestCase
{
    public function testJsgettextCommand()
    {
        $output = __DIR__ . '/Resources/dump/' . $this->generateRandomFileName();

        exec(__DIR__ . '/../../../bin/jsgettext -o "' . $output . '" -k "__" "' . __DIR__ . '/Resources/test.js" "' . __DIR__ . '/Resources/test.html"');

        $parser = new PoeditParser($output);
        $file = $parser->parse();

        $this->assertInstanceOf('\Jsgettext\Poedit\PoeditFile', $file);
        $this->assertCount(15, $file->getStrings());

        unlink($output);
    }
}