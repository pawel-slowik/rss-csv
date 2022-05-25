<?php

declare(strict_types=1);

namespace RssClient\Reader;

use Laminas\Feed\Reader\Reader as FeedReader;

class Reader implements ReaderInterface
{
    private EntryFactory $entryFactory;

    public function __construct(EntryFactory $entryFactory)
    {
        $this->entryFactory = $entryFactory;
    }

    public function fetchIter(string $url): iterable
    {
        $feed = FeedReader::import($url);
        $feedAuthors = $feed->getAuthors();
        foreach ($feed as $entry) {
            yield $this->entryFactory->fromFeedEntryAndAuthors($entry, $feedAuthors);
        }
    }
}
