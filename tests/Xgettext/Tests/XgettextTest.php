<?php

namespace Xgettext\Tests;

use Xgettext\Xgettext,
    Xgettext\Parser\PoeditParser,
    Xgettext\Tests\TestCase;

class XgettextTest extends TestCase
{
    public function testXgettext()
    {
        $output = __DIR__.'/Resources/dump/' . $this->generateRandomFileName();
        $files = array(__DIR__.'/Resources/test.html', __DIR__.'/Resources/test.js');

        new Xgettext($files, $output, array('__'));

        $parser = new PoeditParser($output);
        $file = $parser->parse();

        $this->assertCount(17, $file->getStrings());
        unlink($output);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testWrongFiles()
    {
        new Xgettext(array(), 'foo', array('__'), true);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testWrongOutputFile()
    {
        new Xgettext(array('file'), '');
    }
}
