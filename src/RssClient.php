<?php

declare(strict_types=1);

namespace RssClient;

use Laminas\Feed\Reader\Reader as FeedReader;
use League\Csv\Writer as CsvWriter;

use RssClient\Writer\WriterInterface;

class RssClient
{
    protected $writer;

    protected $entryFormatter;

    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
        $this->entryFormatter = new EntryFormatter();
    }

    public function readAndSave(string $inputUrl, string $outputFilename): void
    {
        $input = $this->fetchIter($inputUrl);
        $formatted = $this->formatIter($input);
        list($header, $converted) = $this->convert($formatted);
        $this->write($outputFilename, $header, $converted);
    }

    protected function fetchIter(string $url): Iterable
    {
        $feed = FeedReader::import($url);
        $feedAuthors = $feed->getAuthors();
        foreach ($feed as $entry) {
            yield new Entry(
                $entry->getTitle(),
                $entry->getDescription(),
                $entry->getLink(),
                $entry->getDateModified(),
                $this->getEntryCreator($entry->getAuthors(), $feedAuthors)
            );
        }
    }

    protected function getEntryCreator($entryAuthors, $feedAuthors): ?string
    {
        if (!$entryAuthors && !$feedAuthors) {
            return null;
        }
        $authors = ($entryAuthors) ? $entryAuthors : $feedAuthors;

        return implode(' ', array_map(
            function ($author) {
                return $author['email'] . ' ' . $author['name'];
            },
            iterator_to_array($authors)
        ));
    }

    protected function formatIter(Iterable $entries): Iterable
    {
        foreach ($entries as $entry) {
            yield $this->entryFormatter->format($entry);
        }
    }

    protected function convert(Iterable $data): array
    {
        $csvData = CsvWriter::createFromString();
        $csvHeader = CsvWriter::createFromString();
        $headerDone = false;
        foreach ($data as $entry) {
            if (!$headerDone) {
                $csvHeader->insertOne(array_keys($entry));
                $headerDone = true;
            }
            $csvData->insertOne($entry);
        }

        return [$csvHeader->getContent(), $csvData->getContent()];
    }

    protected function write(string $path, string $header, string $data): void
    {
        $this->writer->write($path, $header, $data);
    }
}
