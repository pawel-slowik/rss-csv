<?php

declare(strict_types=1);

namespace RssClient\Reader;

use Laminas\Feed\Reader\Reader as FeedReader;
use RssClient\EntryFactory;

class Reader implements ReaderInterface
{
    private $entryFactory;

    public function __construct()
    {
        $this->entryFactory = new EntryFactory();
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
