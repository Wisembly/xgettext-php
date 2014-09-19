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
        $this->file->addString(new PoeditString('bar\n\tbaz', 'bar\n\tbaz'));
    }

    public function testDump()
    {
        $filename = $this->generateRandomFileName();
        $basePath = __DIR__ . '/../Resources/dump';
        $output = $basePath . '/' . $filename;

        $dumper = new JsonDumper($output);
        $dumper->dump($this->file);

        $parser = new PoeditParser($output);
        $content = file_get_contents($output);

        $this->assertEquals($content, '{"qux":"bux","bar":"qux","bar\n\tbaz":"bar\n\tbaz"}');
        unlink($output);
    }
}
