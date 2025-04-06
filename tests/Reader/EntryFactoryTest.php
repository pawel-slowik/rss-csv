<?php

declare(strict_types=1);

namespace RssClient\Test\Reader;

use ArrayIterator;
use DateTime;
use Laminas\Feed\Reader\Entry\EntryInterface;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use RssClient\Reader\EntryFactory;
use UnexpectedValueException;

/**
 * @covers \RssClient\Reader\EntryFactory
 */
class EntryFactoryTest extends TestCase
{
    private EntryFactory $entryFactory;

    private EntryInterface&Stub $feedEntry;

    protected function setUp(): void
    {
        $this->feedEntry = $this->createStub(EntryInterface::class);
        $this->feedEntry->method('getTitle')->willReturn('foo');
        $this->feedEntry->method('getDescription')->willReturn('bar');
        $this->feedEntry->method('getLink')->willReturn('baz');
        $this->feedEntry->method('getDateModified')->willReturn((new DateTime())->setTimestamp(1743936830));

        $this->entryFactory = new EntryFactory();
    }

    public function testProperties(): void
    {
        $entry = $this->entryFactory->fromFeedEntryAndAuthors($this->feedEntry, null);

        $this->assertSame('foo', $entry->title);
        $this->assertSame('bar', $entry->description);
        $this->assertSame('baz', $entry->link);
        $this->assertSame(1743936830, $entry->pubDate->getTimestamp());
    }

    /**
     * @param null|iterable<array{'email': string, 'name': string}> $entryAuthors
     * @param null|iterable<array{'email': string, 'name': string}> $feedAuthors
     *
     * @dataProvider missingCreatorDataProvider
     */
    public function testMissingCreator(?iterable $entryAuthors, ?iterable $feedAuthors): void
    {
        $this->feedEntry->method('getAuthors')->willReturn($entryAuthors);

        $entry = $this->entryFactory->fromFeedEntryAndAuthors($this->feedEntry, $feedAuthors);

        $this->assertNull($entry->creator);
    }

    /**
     * @return array<array{0: null|iterable<mixed>, 1: null|iterable<mixed>}>
     */
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

        $this->assertSame('alice@example.com Alice', $entry->creator);
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

        $this->assertSame('alice@example.com Alice', $entry->creator);
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

        $this->assertSame('bob@example.com Bob', $entry->creator);
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

        $this->assertSame('bob@example.com Bob', $entry->creator);
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

        $this->assertSame('bob@example.com Bob', $entry->creator);
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

        $this->assertSame('alice@example.com Alice', $entry->creator);
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

        $this->assertSame('alice@example.com Alice bob@example.com Bob', $entry->creator);
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

        $this->assertSame('jane@example.org Jane john@example.org John', $entry->creator);
    }

    public function testShouldUseModificationDateIfAvailable(): void
    {
        $feedEntry = $this->createEmptyEntryStub();
        $feedEntry->method('getDateCreated')->willReturn((new DateTime())->setTimestamp(1_222_333_444));
        $feedEntry->method('getDateModified')->willReturn((new DateTime())->setTimestamp(1_222_333_555));

        $entry = $this->entryFactory->fromFeedEntryAndAuthors($feedEntry, null);

        $this->assertSame(1_222_333_555, $entry->pubDate->getTimestamp());
    }

    public function testShouldUseCreationDateIfModificationDateNotAvailable(): void
    {
        $feedEntry = $this->createEmptyEntryStub();
        $feedEntry->method('getDateCreated')->willReturn((new DateTime())->setTimestamp(1_222_333_444));
        $feedEntry->method('getDateModified')->willReturn(null);

        $entry = $this->entryFactory->fromFeedEntryAndAuthors($feedEntry, null);

        $this->assertSame(1_222_333_444, $entry->pubDate->getTimestamp());
    }

    public function testShouldThrowExceptionWhenNoDateAvailable(): void
    {
        $feedEntry = $this->createEmptyEntryStub();
        $feedEntry->method('getDateCreated')->willReturn(null);
        $feedEntry->method('getDateModified')->willReturn(null);

        $this->expectException(UnexpectedValueException::class);

        $this->entryFactory->fromFeedEntryAndAuthors($feedEntry, null);
    }

    public function testShouldHandleNullDescriptions(): void
    {
        $feedEntry = $this->createStub(EntryInterface::class);
        $feedEntry->method('getTitle')->willReturn('');
        $feedEntry->method('getDescription')->willReturn(null);
        $feedEntry->method('getLink')->willReturn('');
        $feedEntry->method('getDateModified')->willReturn(new DateTime());

        $entry = $this->entryFactory->fromFeedEntryAndAuthors($feedEntry, null);

        $this->assertSame('', $entry->description);
    }

    private function createEmptyEntryStub(): EntryInterface&Stub
    {
        $feedEntry = $this->createStub(EntryInterface::class);
        $feedEntry->method('getTitle')->willReturn('');
        $feedEntry->method('getDescription')->willReturn('');
        $feedEntry->method('getLink')->willReturn('');

        return $feedEntry;
    }
}
