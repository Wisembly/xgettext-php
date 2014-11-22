<?php

namespace Xgettext\Tests\Dumper;

use Xgettext\Tests\TestCase,
    Xgettext\Poedit\PoeditFile,
    Xgettext\Poedit\PoeditString,
    Xgettext\Poedit\PoeditPluralString,
    Xgettext\Dumper\PoeditDumper,
    Xgettext\Parser\PoeditParser;

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
        unlink($output);

        $strings = $file->getStrings();
        $this->assertCount(3, $strings);
        $this->assertTrue($file->getString('foo')->isFuzzy());

        $this->assertEquals($strings[0]->getKey(), 'bar');
        $this->assertEquals($strings[1]->getKey(), 'foo');
        $this->assertEquals($strings[2]->getKey(), 'qux');
        $this->assertEquals($file->getString('foo')->getComments(), array('bar', 'baz', 'foo:35', 'foo:56'));
    }

    public function testPluralDump()
    {
        $this->file = new PoeditFile();
        $this->file->addString(new PoeditPluralString('foo', 'foo plural', array('plural one', 'plural two', 'last plural')));

        $filename = $this->generateRandomFileName();
        $basePath = __DIR__ . '/../Resources/dump';
        $output = $basePath . '/' . $filename;

        $dumper = new PoeditDumper($output);
        $dumper->dump($this->file, null, true);

        $parser = new PoeditParser($output);
        $file = $parser->parse();
        unlink($output);

        $strings = $file->getStrings();
        $this->assertCount(1, $strings);
        $this->assertTrue($file->hasString('foo'));
        $this->assertEquals($file->getString('foo')->getPlurals(), array('plural one', 'plural two', 'last plural'));
    }
}
