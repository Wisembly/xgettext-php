<?php

namespace Xgettext\Tests\File;

use \org\bovigo\vfs\vfsStream;

use Xgettext\Tests\TestCase,
    Xgettext\File\File;

class FileTest extends TestCase
{
    public function setUp()
    {
        $this->root = vfsStream::setup('root', null, array(
            'foo' => array(
                'bar.po' => 'bar baz',
                'baz.po' => 'baz bar',
            ),
            'empty' => array(),
        ));
    }

    public function testMkdirr()
    {
        $this->assertTrue(File::mkdirr(vfsStream::url('root/foo')));
        $this->assertFalse(File::mkdirr(vfsStream::url('root/foo/bar.po')));

        $this->assertTrue(File::mkdirr(vfsStream::url('root/foo/foo.po')));
        $this->assertTrue($this->root->hasChild('foo/foo.po'));

        $this->assertTrue(File::mkdirr(vfsStream::url('root/bar/foo.po')));
        $this->assertTrue($this->root->hasChild('bar/foo.po'));
    }
}
