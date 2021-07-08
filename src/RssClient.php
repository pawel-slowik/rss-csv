<?php

declare(strict_types=1);

namespace RssClient;

use Laminas\Feed\Reader\Reader as FeedReader;
use RssClient\Writer\WriterInterface;

class RssClient
{
    private $writer;

    private $entryFactory;

    private $converter;

    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
        $this->entryFactory = new EntryFactory();
        $this->converter = new Converter();
    }

    public function readAndSave(string $inputUrl, string $outputFilename): void
    {
        $input = $this->fetchIter($inputUrl);
        $output = $this->converter->convert($input);
        $this->writer->write($outputFilename, $output->getHeader(), $output->getData());
    }

    protected function fetchIter(string $url): iterable
    {
        $feed = FeedReader::import($url);
        $feedAuthors = $feed->getAuthors();
        foreach ($feed as $entry) {
            yield $this->entryFactory->fromFeedEntryAndAuthors($entry, $feedAuthors);
        }
    }
}
