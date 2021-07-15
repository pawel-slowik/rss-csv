<?php

declare(strict_types=1);

namespace RssClient\Converter;

use ArrayIterator;
use DateTime;
use PHPUnit\Framework\TestCase;
use RssClient\Converter\Formatter\DateFormatter;
use RssClient\Converter\Formatter\DescriptionFormatter;
use RssClient\Reader\Entry;

/**
 * @covers \RssClient\Converter\Converter
 */
class ConverterTest extends TestCase
{
    private const EXPECTED_HEADER = "title,description,link,pubDate,creator\n";

    private $converter;

    protected function setUp(): void
    {
        $this->converter = new Converter(
            $this->createStub(DateFormatter::class),
            $this->createStub(DescriptionFormatter::class)
        );
    }

    /**
     * @dataProvider lineCountDataProvider
     */
    public function testHeaderForArray(array $entries): void
    {
        $output = $this->converter->convert($entries);

        $this->assertSame(self::EXPECTED_HEADER, $output->getHeader());
    }

    /**
     * @dataProvider lineCountDataProvider
     */
    public function testHeaderForIterable(array $entries): void
    {
        $output = $this->converter->convert(new ArrayIterator($entries));

        $this->assertSame(self::EXPECTED_HEADER, $output->getHeader());
    }

    /**
     * @dataProvider lineCountDataProvider
     */
    public function testLineCountForArray(array $entries): void
    {
        $output = $this->converter->convert($entries);

        $entryCount = count($entries);
        $lineCount = substr_count($output->getData(), "\n");

        $this->assertSame($entryCount, $lineCount);
    }

    /**
     * @dataProvider lineCountDataProvider
     */
    public function testLineCountForIterable(array $entries): void
    {
        $output = $this->converter->convert(new ArrayIterator($entries));

        $entryCount = count($entries);
        $lineCount = substr_count($output->getData(), "\n");

        $this->assertSame($entryCount, $lineCount);
    }

    public function lineCountDataProvider(): array
    {
        $entry = $this->createEmptyEntryStub();

        return [
            [
                []
            ],
            [
                [$entry]
            ],
            [
                array_fill(0, 100, $entry)
            ],
        ];
    }

    private function createEmptyEntryStub(): Entry
    {
        $entry = $this->createStub(Entry::class);
        $entry->method('getTitle')->willReturn('');
        $entry->method('getDescription')->willReturn('');
        $entry->method('getLink')->willReturn('');
        $entry->method('getPubDate')->willReturn(new DateTime());
        $entry->method('getCreator')->willReturn('');

        return $entry;
    }
}
