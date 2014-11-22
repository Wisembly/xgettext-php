<?php

namespace Xgettext\Tests\Parser;

use Xgettext\Tests\TestCase,
    Xgettext\Parser\PoeditParser;

class PoeditParserTest extends TestCase
{
    public function testParseSimplePoeditFile()
    {
        $parser = new PoeditParser(__DIR__ . '/../Resources/simple.po');
        $content = $parser->parse();

        $strings = $content->getStrings();
        $this->assertInstanceOf('\Xgettext\Poedit\PoeditFile', $content);
        $this->assertCount(3, $strings);
        $this->assertCount(0, $content->getString('Edit')->getComments());
    }

    public function testParsePoeditFile()
    {
        $parser = new PoeditParser(__DIR__ . '/../Resources/full.po');
        $file = $parser->parse();

        $strings = $file->getStrings();
        $this->assertInstanceOf('\Xgettext\Poedit\PoeditFile', $file);
        $this->assertCount(5, $strings);
        $this->assertCount(9, $file->getString('Edit')->getReferences());

        $this->assertTrue($file->hasString('Download \\\'escaped simple quotes\\\''));
        $this->assertTrue($file->hasString('Preview "with double quotes"'));
        $this->assertTrue($file->getString('deprecated')->isDeprecated());

        $this->assertTrue($file->hasString('<p>This is here a particular case where the key is so long that it could not fit in a single line, on the same msgid line.</p>'));
        $this->assertEquals(
            '<p>Same here, the translation is too long to fit on a single line, it is below msgstr line too</p>',
            $file->getString('<p>This is here a particular case where the key is so long that it could not fit in a single line, on the same msgid line.</p>')->getValue()
        );
    }

    public function testPluralParsing()
    {
        $content = <<<EOT
msgid "foo"
msgid_plural "bar"
msgstr[0] "baz"
msgstr[1] "qux"
msgstr[2] "bux"

msgid ""
"bar"
msgid_plural ""
"baz"
msgstr[0] "baz "
"bux"
msgstr[1] "qux "
"foo"
"bar"
EOT;

        $parser = new PoeditParser('foo');
        $file = $parser->parse($content);
        $this->assertCount(3, $file->getString('foo')->getPlurals());

        $this->assertCount(2, $file->getString('bar')->getPlurals());
        $this->assertEquals('baz bux', $file->getString('bar')->getPlural(0));
    }
}
