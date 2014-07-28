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
        $this->file->addString(new PoeditString('foo', 'bar', array('baz', 'foo:56', 'foo:35','bar')));
        $this->file->getString('foo')->setFuzzy(true);
        $this->file->addString(new PoeditString('qux', 'bux'));
        $this->file->addString(new PoeditString('bar', 'qux'));
    }

    public function testDump()
    {
        $filename = $this->generateRandomFileName();
        $basePath = __DIR__ . '/../Resources/dump';
        $output = $basePath . '/' . $filename;

        $dumper = new PoeditDumper($output);
        $dumper->dump($this->file);

        $parser = new PoeditParser($output);
        $file = $parser->parse();

        $strings = $file->getStrings();

        $this->assertCount(3, $strings);
        $this->assertEquals($strings[0]->getKey(), 'foo');
        $this->assertEquals($strings[1]->getKey(), 'qux');
        $this->assertEquals($strings[2]->getKey(), 'bar');
        $this->assertEquals($file->getString('foo')->getComments(), array('baz', 'foo:56', 'foo:35','bar'));

        unlink($output);
    }

    public function testSortedDump()
    {
        $filename = $this->generateRandomFileName();
        $basePath = __DIR__ . '/../Resources/dump';
        $output = $basePath . '/' . $filename;

        $dumper = new PoeditDumper($output);
        $dumper->dump($this->file, null, true);

        $parser = new PoeditParser($output);
        $file = $parser->parse();

        $this->assertCount(3, $file->getStrings());
        $this->assertTrue($file->getString('foo')->isFuzzy());

        $strings = $file->getStrings();

        $this->assertEquals($strings[0]->getKey(), 'bar');
        $this->assertEquals($strings[1]->getKey(), 'foo');
        $this->assertEquals($strings[2]->getKey(), 'qux');
        $this->assertEquals($file->getString('foo')->getComments(), array('bar', 'baz', 'foo:35', 'foo:56'));

        unlink($output);
    }
}
