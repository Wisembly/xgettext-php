<?php

namespace Xgettext\Dumper;

use Xgettext\File\File,
    Xgettext\Poedit\PoeditFile,
    Xgettext\Poedit\PoeditString,
    Xgettext\Poedit\PoeditPluralString,
    Xgettext\Exception\MissingFileLanguageException;

class JsonDumper implements DumperInterface
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
        $pluralForms = null;
        $filename = null !== $filename ? $filename : $this->file;

        $strings = $file->getTranslated();

        foreach ($strings as $string) {
            if ($string instanceof PoeditPluralString) {

                // if we found a plural form, we need to look for file language
                if (null === $pluralForms) {
                    $pluralForms = $this->getLangPluralForm($file->getLang());
                }

                if (null === $file->getLang()) {
                    throw new MissingFileLanguageException('Your .po file must have a language defined in order to dump plural forms', 1);
                }

                $content[$string->getKey()] = array_combine($pluralForms, $string->getPlurals());
            } else {
                $content[$string->getKey()] = $string->getValue();
            }
        }

        $content = json_encode($content, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);

        // fix some unwanted backslashes, auto-escaped by json_encode()
        $content = str_replace(array('\\\\n', '\\\\r', '\\\\t'), array('\n', '\r', '\t'), $content);

        // ensure that path and file exists
        File::mkdirr(substr($filename, 0, strrpos($filename, '/')));

        return false !== file_put_contents($filename, $content);
    }

    private function getLangPluralForm($lang)
    {
        $rules = $this->getRules();
        $plurals = $this->getPlurals();

        if (!isset($rules[$lang])) {
            throw new \Exception(sprintf('Language %s is not supported', $lang), 1);
        }

        return $plurals[$rules[$lang]];
    }

    private function getPlurals()
    {
        return array(
            0   => array('one'),
            1   => array('other'),
            2   => array('one', 'other'),
            3   => array('one', 'other', 'zero'),
            4   => array('one', 'two', 'other'),
            5   => array('one', 'few', 'other'),
            6   => array('one', 'two', 'few', 'other'),
            7   => array('one', 'few', 'many', 'other'),
            8   => array('zero', 'one', 'two', 'few', 'many', 'other'),
        );
    }

    private function getRules()
    {
        return array_merge(
            array_fill_keys(array('az', 'bm', 'my', 'zh', 'dz', 'ka', 'hu', 'ig', 'id', 'ja', 'jv', 'kea', 'kn', 'km', 'ko', 'ses', 'lo', 'kde', 'ms', 'fa', 'root', 'sah', 'sg', 'ii', 'th', 'bo', 'to', 'tr', 'vi', 'wo', 'yo'), 1),
            array_fill_keys(array('gv', 'tzm', 'mk', 'fr', 'ff', 'kab', 'ak', 'am', 'bh', 'fil', 'guw', 'hi', 'ln', 'mg', 'nso', 'tl', 'ti', 'wa', 'af', 'sq', 'eu', 'bem', 'bn', 'brx', 'bg', 'ca', 'chr', 'cgg', 'da', 'dv', 'nl', 'en', 'eo', 'et', 'ee', 'fo', 'fi', 'fur', 'gl', 'lg', 'de', 'el', 'gu', 'ha', 'haw', 'he', 'is', 'it', 'kl', 'kk', 'ku', 'lb', 'ml', 'mr', 'mas', 'mn', 'nah', 'ne', 'no', 'nb', 'nn', 'nyn', 'or', 'om', 'pap', 'ps', 'pt', 'pa', 'rm', 'ssy', 'saq', 'xog', 'so', 'es', 'sw', 'sv', 'gsw', 'syr', 'ta', 'te', 'tk', 'ur', 'wae', 'fy', 'zu'), 2),
            array_fill_keys(array('lv', 'ksh', 'lag'), 3),
            array_fill_keys(array('kw', 'smn', 'iu', 'ga', 'smj', 'se', 'smi', 'sms', 'sma'), 4),
            array_fill_keys(array('be', 'bs', 'hr', 'ru', 'sr', 'sh', 'uk', 'pl', 'mt'), 7),
            array_fill_keys(array('lt', 'shi', 'mo', 'ro', 'cs', 'sk'), 5),
            array_fill_keys(array('sl'), 6),
            array_fill_keys(array('ar', 'br', 'cy'), 8)
        );
    }
}
