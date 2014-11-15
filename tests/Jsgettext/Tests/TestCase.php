<?php

namespace Jsgettext\Tests;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected function generateRandomFileName($ext = 'po')
    {
        return 'f' . uniqid() . '.' . $ext;
    }
}
