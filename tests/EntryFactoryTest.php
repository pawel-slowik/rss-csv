<?php

declare(strict_types=1);

namespace RssClient;

use ArrayIterator;
use DateTime;
use Laminas\Feed\Reader\Entry\EntryInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \RssClient\EntryFactory
 */
class EntryFactoryTest extends TestCase
{
    private $entryFactory;

    private $feedEntry;

    private $dateModified;

    protected function setUp(): void
    {
        $this->feedEntry = $this->createStub(EntryInterface::class);
        $this->feedEntry->method('getTitle')->willReturn('foo');
        $this->feedEntry->method('getDescription')->willReturn('bar');
        $this->feedEntry->method('getLink')->willReturn('baz');
        $this->dateModified = $this->createStub(DateTime::class);
        $this->feedEntry->method('getDateModified')->willReturn($this->dateModified);

        $this->entryFactory = new EntryFactory();
    }

    public function testProperties(): void
    {
        $entry = $this->entryFactory->fromFeedEntryAndAuthors($this->feedEntry, null);

        $this->assertSame('foo', $entry->getTitle());
        $this->assertSame('bar', $entry->getDescription());
        $this->assertSame('baz', $entry->getLink());
        $this->assertSame($this->dateModified, $entry->getPubDate());
    }

    /**
     * @dataProvider missingCreatorDataProvider
     */
    public function testMissingCreator(?iterable $entryAuthors, ?iterable $feedAuthors): void
    {
        $this->feedEntry->method('getAuthors')->willReturn($entryAuthors);

        $entry = $this->entryFactory->fromFeedEntryAndAuthors($this->feedEntry, $feedAuthors);

        $this->assertNull($entry->getCreator());
    }

    public function missingCreatorDataProvider(): array
    {
        return [
            [null, null],
            [null, []],
            [null, new ArrayIterator()],
            [[], null],
            [[], []],
            [[], new ArrayIterator()],
            [new ArrayIterator(), null],
            [new ArrayIterator(), []],
            [new ArrayIterator(), new ArrayIterator()],
        ];
    }

    public function testSupportsArrayAsEntryAuthors(): void
    {
        $this->feedEntry
            ->method('getAuthors')
            ->willReturn(
                [['name' => 'Alice', 'email' => 'alice@example.com']]
            );

        $entry = $this->entryFactory->fromFeedEntryAndAuthors($this->feedEntry, null);

        $this->assertSame('alice@example.com Alice', $entry->getCreator());
    }

    public function testSupportsIterableAsEntryAuthors(): void
    {
        $this->feedEntry
            ->method('getAuthors')
            ->willReturn(
                new ArrayIterator(
                    [['name' => 'Alice', 'email' => 'alice@example.com']]
                )
            );

        $entry = $this->entryFactory->fromFeedEntryAndAuthors($this->feedEntry, null);

        $this->assertSame('alice@example.com Alice', $entry->getCreator());
    }

    public function testSupportsArrayAsFeedAuthors(): void
    {
        $this->feedEntry
            ->method('getAuthors')
            ->willReturn(null);

        $entry = $this->entryFactory->fromFeedEntryAndAuthors(
            $this->feedEntry,
            [['name' => 'Bob', 'email' => 'bob@example.com']]
        );

        $this->assertSame('bob@example.com Bob', $entry->getCreator());
    }

    public function testSupportsIterableAsFeedAuthors(): void
    {
        $this->feedEntry->method('getAuthors')->willReturn(null);

        $entry = $this->entryFactory->fromFeedEntryAndAuthors(
            $this->feedEntry,
            new ArrayIterator(
                [['name' => 'Bob', 'email' => 'bob@example.com']]
            )
        );

        $this->assertSame('bob@example.com Bob', $entry->getCreator());
    }

    public function testShouldUseFeedAuthorsIfEntryAuthorsNotAvailable(): void
    {
        $this->feedEntry
            ->method('getAuthors')
            ->willReturn([]);

        $entry = $this->entryFactory->fromFeedEntryAndAuthors(
            $this->feedEntry,
            [['name' => 'Bob', 'email' => 'bob@example.com']]
        );

        $this->assertSame('bob@example.com Bob', $entry->getCreator());
    }

    public function testShouldNotUseFeedAuthorsIfEntryAuthorsAvailable(): void
    {
        $this->feedEntry
            ->method('getAuthors')
            ->willReturn(
                [['name' => 'Alice', 'email' => 'alice@example.com']]
            );

        $entry = $this->entryFactory->fromFeedEntryAndAuthors(
            $this->feedEntry,
            []
        );

        $this->assertSame('alice@example.com Alice', $entry->getCreator());
    }

    public function testShouldMergeMultipleEntryAuthors(): void
    {
        $this->feedEntry
            ->method('getAuthors')
            ->willReturn(
                [
                    ['name' => 'Alice', 'email' => 'alice@example.com'],
                    ['name' => 'Bob', 'email' => 'bob@example.com'],
                ]
            );

        $entry = $this->entryFactory->fromFeedEntryAndAuthors(
            $this->feedEntry,
            []
        );

        $this->assertSame('alice@example.com Alice bob@example.com Bob', $entry->getCreator());
    }

    public function testShouldMergeMultipleFeedAuthors(): void
    {
        $this->feedEntry
            ->method('getAuthors')
            ->willReturn([]);

        $entry = $this->entryFactory->fromFeedEntryAndAuthors(
            $this->feedEntry,
            [
                ['name' => 'Jane', 'email' => 'jane@example.org'],
                ['name' => 'John', 'email' => 'john@example.org'],
            ],
        );

        $this->assertSame('jane@example.org Jane john@example.org John', $entry->getCreator());
    }
}
