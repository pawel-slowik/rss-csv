<?php

declare(strict_types=1);

namespace RssClient;

use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * @covers \RssClient\Entry
 */
class EntryTest extends TestCase
{
    private $entry;

    private $pubDate;

    protected function setUp(): void
    {
        $this->pubDate = $this->createStub(DateTime::class);

        $this->entry = new Entry(
            'foo',
            'bar',
            'baz',
            $this->pubDate,
            'qux'
        );
    }

    public function testGetters(): void
    {
        $this->assertSame('foo', $this->entry->getTitle());
        $this->assertSame('bar', $this->entry->getDescription());
        $this->assertSame('baz', $this->entry->getLink());
        $this->assertSame($this->pubDate, $this->entry->getPubDate());
        $this->assertSame('qux', $this->entry->getCreator());
    }
}
