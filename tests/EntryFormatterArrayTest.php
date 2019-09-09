<?php

declare(strict_types=1);

namespace PawelSlowikRekrutacjaHRtec\RssReader;

use PHPUnit\Framework\TestCase;

class EntryFormatterArrayTest extends TestCase
{
    protected $formatter;

    protected $emptyEntry;

    protected function setUp(): void
    {
        $this->formatter = new EntryFormatter();
        $this->emptyEntry = [
            'title' => '',
            'description' => '',
            'link' => '',
            'pubDate' => new \DateTime(),
            'creator' => '',
        ];
    }

    public function testReturnsArray(): void
    {
        $formatted = $this->formatter->format($this->emptyEntry);
        $this->assertIsArray($formatted);
    }

    public function testReturnsSameKeys(): void
    {
        $formatted = $this->formatter->format($this->emptyEntry);
        foreach ($this->emptyEntry as $key => $value) {
            $this->assertArrayHasKey($key, $formatted);
        }
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
