#!/usr/bin/env php
<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

use RssClient\Command\CsvCommand;
use RssClient\Converter\Converter;
use RssClient\Converter\Formatter\DateFormatter;
use RssClient\Converter\Formatter\DescriptionFormatter;
use RssClient\Reader\EntryFactory;
use RssClient\Reader\Reader;
use RssClient\RssClient;
use RssClient\Writer\AppendWriter;
use RssClient\Writer\OverwriteWriter;

$application = new Application();
$reader = new Reader(new EntryFactory());
$converter = new Converter(new DateFormatter(), new DescriptionFormatter());

$application->add(
    new CsvCommand(
        new RssClient($reader, new OverwriteWriter(), $converter),
        'csv:simple',
        'Download a RSS/Atom feed and save its contents into a CSV file.'
    )
);

$application->add(
    new CsvCommand(
        new RssClient($reader, new AppendWriter(), $converter),
        'csv:extended',
        'Download a RSS/Atom feed and append its contents to a CSV file.'
    )
);

$application->run();
