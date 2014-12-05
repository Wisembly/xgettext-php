<?php

namespace Xgettext\Dumper;

use Xgettext\File\File,
    Xgettext\Poedit\PoeditFile,
    Xgettext\Poedit\PoeditString,
    Xgettext\Poedit\PoeditPluralString,
    Xgettext\Exception\MissingFileLanguageException;

class CsvDumper implements DumperInterface
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
    *   Dump PoeditFile translated keys into key/value .json file
    *
    *   @param PoeditFile   $file
    *   @param string       $filename
    *
    *   @throws MissingFileLanguageException   if no language is set and we have plural forms
    *
    *   @return boolean
    */
    public function dump(PoeditFile $file, $filename = null)
    {
        $content = array();
        $filename = null !== $filename ? $filename : $this->file;

        $strings = $file->getTranslated();

        foreach ($strings as $string) {
            if ($string instanceof PoeditPluralString) {
                if (null === $file->getLang()) {
                    throw new MissingFileLanguageException('Your .po file must have a language defined in order to dump plural forms', 1);
                }

                $content[$string->getKey()] = $string->getPlurals();
            } else {
                $content[$string->getKey()] = $string->getValue();
            }
        }

        $csv = "msgid;msgid_plural;msgstr;plural0;plural1;plural2;plural3;plural4;plural5" . PHP_EOL;

        foreach ($strings as $string) {
            $plurals = array();
            $csv .= $string->getKey() . ';';

            if ($string instanceof PoeditPluralString) {
                $plurals = $string->getPlurals();
                $csv .= $string->getPluralForm() . ';;';
            } else {
                $csv .= ';' . $string->getValue() . ';';
            }

            for ($i = 0; $i < 6; $i++) {
                if (isset($plurals[$i])) {
                    $csv .= $plurals[$i] . ";";
                } else {
                    $csv .= ";";
                }
            }

            $csv .= PHP_EOL;
        }

        // ensure that path and file exists
        File::mkdirr(substr($filename, 0, strrpos($filename, '/')));

        return false !== file_put_contents($filename, $csv);
    }
}
