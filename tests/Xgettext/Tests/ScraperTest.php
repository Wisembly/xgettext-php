<?php

namespace Xgettext\Tests;

use Xgettext\Scraper;

class ScraperTest extends TestCase
{
    public function testScraper()
    {
        $scraper = new Scraper(__DIR__.'/Resources/scrap.po');
        $this->assertInstanceOf('Xgettext\Poedit\PoeditFile', $scraper->poeditFile);
    }
}
