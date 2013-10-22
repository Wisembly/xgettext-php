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
        $this->seed = mt_rand(1, 1000);
        $this->file = new PoeditFile();
        $this->file->addString(new PoeditString('foo', 'bar', true, array('baz:' . $this->seed)));
        $this->file->addString(new PoeditString('qux', 'bux'));
    }

    public function testDump()
    {
        $output = __DIR__ . '/../Resources/dump/dump.po';

        $dumper = new PoeditDumper($output);
        $dumper->dump($this->file);

        $parser = new PoeditParser($output);
        $file = $parser->parse();

        $this->assertCount(2, $file->getStrings());
        $this->assertTrue($file->getString('foo')->isFuzzy());
        $this->assertEquals($file->getString('foo')->getComments(), array('baz:' . $this->seed));

        unlink($output);
    }
}