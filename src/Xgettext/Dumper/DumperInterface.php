<?php

namespace Xgettext\Dumper;

use Xgettext\Poedit\PoeditFile;

interface DumperInterface
{
    public function __construct($file);

    public function dump(PoeditFile $file, $filename = null);
}
