<?php

declare(strict_types=1);

namespace RssClient\Converter;

use League\Csv\Writer;
use RssClient\Converter\Formatter\DateFormatter;
use RssClient\Converter\Formatter\DescriptionFormatter;
use RssClient\Writer\Output;

class Converter implements ConverterInterface
{
    private DateFormatter $dateFormatter;

    private DescriptionFormatter $descriptionFormatter;

    public function __construct(
        DateFormatter $dateFormatter,
        DescriptionFormatter $descriptionFormatter
    ) {
        $this->dateFormatter = $dateFormatter;
        $this->descriptionFormatter = $descriptionFormatter;
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
                    $entry->title,
                    $this->descriptionFormatter->format($entry->description),
                    $entry->link,
                    $this->dateFormatter->format($entry->pubDate),
                    $entry->creator,
                ]
            );
        }

        return new Output($csvHeader->getContent(), $csvData->getContent());
    }
}
