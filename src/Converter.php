<?php

declare(strict_types=1);

namespace RssClient;

use League\Csv\Writer;

class Converter
{
    private $entryFormatter;

    public function __construct()
    {
        $this->entryFormatter = new EntryFormatter();
    }

    public function convert(iterable $entries): Output
    {
        $csvHeader = Writer::createFromString();
        $csvHeader->insertOne(
            [
                'title',
                'description',
                'link',
                'pubDate',
                'creator',
            ]
        );

        $csvData = Writer::createFromString();
        foreach ($entries as $entry) {
            $csvData->insertOne(
                [
                    $entry->getTitle(),
                    $this->entryFormatter->formatDescription($entry->getDescription()),
                    $entry->getLink(),
                    $this->entryFormatter->formatDate($entry->getPubDate()),
                    $entry->getCreator(),
                ]
            );
        }

        return new Output($csvHeader->getContent(), $csvData->getContent());
    }
}
