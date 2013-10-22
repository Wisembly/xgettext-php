<?php

namespace Jsgettext\Dumper;

use Jsgettext\Poedit\PoeditFile;

interface DumperInterface
{
    public function dump(PoeditFile $file, $filename = null);
}