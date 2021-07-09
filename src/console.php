#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

use RssClient\Reader\Reader;
use RssClient\RssClient;
use RssClient\Writer\OverwriteWriter;
use RssClient\Writer\AppendWriter;
use RssClient\Command\CsvCommand;

$application = new Application();
$reader = new Reader();

$application->add(
    new CsvCommand(
        new RssClient($reader, new OverwriteWriter()),
        'csv:simple',
        'Download a RSS/Atom feed and save its contents into a CSV file.'
    )
);

$application->add(
    new CsvCommand(
        new RssClient($reader, new AppendWriter()),
        'csv:extended',
        'Download a RSS/Atom feed and append its contents to a CSV file.'
    )
);

$application->run();
