<?php

declare(strict_types=1);

namespace RssClient\Converter;

use League\Csv\Writer;
use RssClient\Converter\Formatter\DateFormatter;
use RssClient\Converter\Formatter\DescriptionFormatter;
use RssClient\Writer\Output;

class Converter implements ConverterInterface
{
    private $dateFormatter;

    private $descriptionFormatter;

    public function __construct()
    {
        $this->dateFormatter = new DateFormatter();
        $this->descriptionFormatter = new DescriptionFormatter();
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
                    $this->descriptionFormatter->format($entry->getDescription()),
                    $entry->getLink(),
                    $this->dateFormatter->format($entry->getPubDate()),
                    $entry->getCreator(),
                ]
            );
        }

        return new Output($csvHeader->getContent(), $csvData->getContent());
    }
}
