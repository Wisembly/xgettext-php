<?php

namespace Xgettext\Tests;

use Xgettext\Tests\TestCase,
    Xgettext\Parser\PoeditParser;

class XgettextCommandTest extends TestCase
{
    public function testXgettextCommand()
    {
        $output = __DIR__ . '/Resources/dump/' . $this->generateRandomFileName();

        exec(__DIR__ . '/../../../bin/xgettext -o "' . $output . '" -k "__" "' . __DIR__ . '/Resources/test.js" "' . __DIR__ . '/Resources/test.html"');

        $parser = new PoeditParser($output);
        $file = $parser->parse();

        $this->assertInstanceOf('\Xgettext\Poedit\PoeditFile', $file);
        $this->assertCount(15, $file->getStrings());

        unlink($output);
    }
}
