<?php

namespace Xgettext;

use \Exception,
    \InvalidArgumentException;

use Xgettext\Poedit\PoeditFile,
    Xgettext\Dumper\PoeditDumper;

class Xgettext
{
    public function __construct(array $files, $output, array $keywords = array('_'), $parser = 'javascript', $enc = 'UTF-8', $cli = false)
    {
        $this->cli = $cli;
        $parser = 'Xgettext\\Parser\\' . ucfirst(strtolower($parser)) . 'Parser';

        if (empty($files)) {
            throw new InvalidArgumentException('You did not provide any input file.');
        }

        if (empty($output)) {
            throw new InvalidArgumentException('You did not provide any output file.');
        }

        $poeditFile = new PoeditFile();

        foreach ($files as $file) {
            try {
                $fileParser = new $parser($file, $keywords);
                $poeditFile->addStrings($fileParser->parse());
            } catch (Exception $e) {
                throw new InvalidArgumentException(sprintf('"%s" parser does not exist', $parser));
            }
        }

        $poeditDumper = new PoeditDumper($output);
        $poeditDumper->dump($poeditFile, null, false, $enc);
    }
}
