<?php

namespace Xgettext\Tests\File;

use \org\bovigo\vfs\vfsStream;

use Xgettext\Tests\TestCase,
    Xgettext\File\Finder;

class FileTest extends TestCase
{
    public function setUp()
    {
        $this->root = vfsStream::setup('root', null, array(
            'dir' => array(
                'bar.html' => 'bar baz',
                'baz.js' => 'baz bar',
                'baz.hbs' => 'baz bar',
                'subdir' => array(
                    'subfoo.html' => 'foo',
                    'subbar.js' => 'bar',
                    'subbaz.hbs' => 'baz'
                ),
            ),
            'empty' => array(),
        ));
    }

    public function testFindr()
    {
        $this->assertCount(4, Finder::findr(vfsStream::url('root/dir')));
        $this->assertCount(2, Finder::findr(vfsStream::url('root/dir'), array('.hbs')));

        $this->assertCount(2, Finder::findr(vfsStream::url('root/dir/subdir')));
        $this->assertCount(0, Finder::findr(vfsStream::url('root/empty')));
    }
}
