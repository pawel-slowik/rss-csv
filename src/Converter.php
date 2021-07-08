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
        $csvData = Writer::createFromString();
        $csvHeader = Writer::createFromString();
        $headerDone = false;

        foreach ($entries as $entry) {
            $formatted = $this->entryFormatter->format($entry);
            if (!$headerDone) {
                $csvHeader->insertOne(array_keys($formatted));
                $headerDone = true;
            }
            $csvData->insertOne($formatted);
        }

        return new Output($csvHeader->getContent(), $csvData->getContent());
    }
}
