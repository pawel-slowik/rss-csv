#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

use RssReader\RssReader;
use RssReader\Writer\OverwriteWriter;
use RssReader\Writer\AppendWriter;
use RssReader\Command\CsvCommand;

$application = new Application();

$application->add(new CsvCommand(
    new RssReader(new OverwriteWriter()),
    'csv:simple',
    'Download a RSS/Atom feed and save its contents into a CSV file.'
));

$application->add(new CsvCommand(
    new RssReader(new AppendWriter()),
    'csv:extended',
    'Download a RSS/Atom feed and append its contents to a CSV file.'
));

$application->run();
