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
        unlink($output);

        $this->assertInstanceOf('\Xgettext\Poedit\PoeditFile', $file);
        $this->assertCount(17, $file->getStrings());
    }

    public function testXgettextHandlebarsCommand()
    {
        $output = __DIR__ . '/Resources/dump/' . $this->generateRandomFileName();

        exec(__DIR__ . '/../../../bin/xgettext -l "handlebars" -o "' . $output . '" -k "_t" -k "_n:1,2" "' . __DIR__ . '/Resources/test.hbs"');

        $parser = new PoeditParser($output);
        $file = $parser->parse();
        unlink($output);

        $this->assertInstanceOf('\Xgettext\Poedit\PoeditFile', $file);
        $this->assertCount(2, $file->getStrings());
    }
}
