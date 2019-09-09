<?php

declare(strict_types=1);

namespace PawelSlowikRekrutacjaHRtec\RssReader;

use PHPUnit\Framework\TestCase;

class RssReaderTest extends TestCase
{
    /**
     * @var RssReader
     */
    protected $rssReader;

    protected function setUp(): void
    {
        $this->rssReader = new RssReader;
    }

    public function testIsInstanceOfRssReader(): void
    {
        $actual = $this->rssReader;
        $this->assertInstanceOf(RssReader::class, $actual);
    }
}
