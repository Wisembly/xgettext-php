<?php

namespace Jsgettext\Tests\Dumper;

use Jsgettext\Tests\TestCase,
    Jsgettext\Poedit\PoeditFile,
    Jsgettext\Poedit\PoeditString,
    Jsgettext\Dumper\PoeditDumper,
    Jsgettext\Parser\PoeditParser;

class PoeditDumperTest extends TestCase
{
    public function setUp()
    {
        $this->file = new PoeditFile();
        $this->file->addString(new PoeditString('foo', 'bar', true, array('baz')));
        $this->file->addString(new PoeditString('qux', 'bux'));
    }

    public function testDump()
    {
        $filename = $this->generateRandomFileName();
        $basePath = __DIR__ . '/../Resources/dump';
        $dir1 = $this->generateRandomFileName(null);
        $dir2 = $this->generateRandomFileName(null);
        $output = $basePath . '/' . $dir1 . '/' . $dir2 . '/' . $filename;

        $dumper = new PoeditDumper($output);
        $dumper->dump($this->file);

        $parser = new PoeditParser($output);
        $file = $parser->parse();

        $this->assertCount(2, $file->getStrings());
        $this->assertTrue($file->getString('foo')->isFuzzy());
        $this->assertEquals($file->getString('foo')->getComments(), array('baz'));

        unlink($output);
        rmdir($basePath . '/' . $dir1 . '/' . $dir2);
        rmdir($basePath . '/' . $dir1);
    }
}