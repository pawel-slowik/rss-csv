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

    private Converter $converter;

    protected function setUp(): void
    {
        $this->converter = new Converter(
            $this->createStub(DateFormatter::class),
            $this->createStub(DescriptionFormatter::class)
        );
    }

    /**
     * @param Entry[] $entries
     * @dataProvider lineCountDataProvider
     */
    public function testHeaderForArray(array $entries): void
    {
        $output = $this->converter->convert($entries);

        $this->assertSame(self::EXPECTED_HEADER, $output->header);
    }

    /**
     * @param Entry[] $entries
     * @dataProvider lineCountDataProvider
     */
    public function testHeaderForIterable(array $entries): void
    {
        $output = $this->converter->convert(new ArrayIterator($entries));

        $this->assertSame(self::EXPECTED_HEADER, $output->header);
    }

    /**
     * @param Entry[] $entries
     * @dataProvider lineCountDataProvider
     */
    public function testLineCountForArray(array $entries): void
    {
        $output = $this->converter->convert($entries);

        $entryCount = count($entries);
        $lineCount = substr_count($output->data, "\n");

        $this->assertSame($entryCount, $lineCount);
    }

    /**
     * @param Entry[] $entries
     * @dataProvider lineCountDataProvider
     */
    public function testLineCountForIterable(array $entries): void
    {
        $output = $this->converter->convert(new ArrayIterator($entries));

        $entryCount = count($entries);
        $lineCount = substr_count($output->data, "\n");

        $this->assertSame($entryCount, $lineCount);
    }

    /**
     * @return array<array{0: Entry[]}>
     */
    public function lineCountDataProvider(): array
    {
        $entry = new Entry('', '', '', new DateTime(), '');

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
}
