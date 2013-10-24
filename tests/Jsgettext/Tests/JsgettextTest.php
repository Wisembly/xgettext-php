<?php

namespace Jsgettext\Tests;

use Jsgettext\Jsgettext,
    Jsgettext\Parser\PoeditParser,
    Jsgettext\Tests\TestCase;

class JsgettextTest extends TestCase
{
    public function testJsgettext()
    {
        $output = __DIR__.'/Resources/dump/' . $this->generateRandomFileName();
        $files = array(__DIR__.'/Resources/test.html', __DIR__.'/Resources/test.js');

        new Jsgettext($files, $output, array('__'));

        $parser = new PoeditParser($output);
        $file = $parser->parse();

        $this->assertCount(15, $file->getStrings());
        unlink($output);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testWrongFiles()
    {
        new Jsgettext(array(), 'foo', array('__'), true);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testWrongOutputFile()
    {
        new Jsgettext(array('file'), '');
    }
}