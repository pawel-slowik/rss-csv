<?php

declare(strict_types=1);

namespace RssClient;

use PHPUnit\Framework\TestCase;

class EntryFormatterArrayTest extends TestCase
{
    protected $formatter;

    protected $emptyEntry;

    protected function setUp(): void
    {
        $this->formatter = new EntryFormatter();
        $this->emptyEntry = $this->createStub(Entry::class);
        $this->emptyEntry->method('getTitle')->willReturn('');
        $this->emptyEntry->method('getDescription')->willReturn('');
        $this->emptyEntry->method('getLink')->willReturn('');
        $this->emptyEntry->method('getPubDate')->willReturn(new \DateTime());
        $this->emptyEntry->method('getCreator')->willReturn('');
    }

    public function testReturnsArray(): void
    {
        $formatted = $this->formatter->format($this->emptyEntry);
        $this->assertIsArray($formatted);
    }

    public function testReturnsAllRequiredKeys(): void
    {
        $formatted = $this->formatter->format($this->emptyEntry);
        $this->assertArrayHasKey('title', $formatted);
        $this->assertArrayHasKey('description', $formatted);
        $this->assertArrayHasKey('link', $formatted);
        $this->assertArrayHasKey('pubDate', $formatted);
        $this->assertArrayHasKey('creator', $formatted);
    }

    public function testReturnsDescriptionAsString(): void
    {
        $formatted = $this->formatter->format($this->emptyEntry);
        $this->assertIsString($formatted['description']);
    }

    public function testReturnsPubDateAsString(): void
    {
        $formatted = $this->formatter->format($this->emptyEntry);
        $this->assertIsString($formatted['pubDate']);
    }
}
