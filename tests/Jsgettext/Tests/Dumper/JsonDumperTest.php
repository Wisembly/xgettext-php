<?php

namespace Jsgettext\Tests\Dumper;

use Jsgettext\Tests\TestCase,
    Jsgettext\Poedit\PoeditFile,
    Jsgettext\Poedit\PoeditString,
    Jsgettext\Dumper\JsonDumper,
    Jsgettext\Parser\PoeditParser;

class JsonDumperTest extends TestCase
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

        $dumper = new JsonDumper($output);
        $dumper->dump($this->file);

        $parser = new PoeditParser($output);
        $file = file_get_contents($output);

        $strings = json_decode($file, true);

        $this->assertCount(2, $strings);
        $this->assertEquals($strings['qux'], 'bux');
        $this->assertEquals($strings['bar'], 'qux');
        $this->assertFalse(isset($strings['foo']));

        unlink($output);
    }
}
