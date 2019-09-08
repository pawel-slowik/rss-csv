#!/usr/bin/env php
<?php

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Console\Application;

use PawelSlowikRekrutacjaHRtec\RssReader\RssReader;
use PawelSlowikRekrutacjaHRtec\RssReader\Writer\OverwriteCsvWriter;
use PawelSlowikRekrutacjaHRtec\RssReader\Writer\AppendCsvWriter;
use PawelSlowikRekrutacjaHRtec\RssReader\Command\CsvCommand;

$application = new Application();

$application->add(new CsvCommand(
    new RssReader(new OverwriteCsvWriter()),
    'csv:simple',
    'Download a RSS/Atom feed and save its contents into a CSV file.'
));

$application->add(new CsvCommand(
    new RssReader(new AppendCsvWriter()),
    'csv:extended',
    'Download a RSS/Atom feed and append its contents to a CSV file.'
));

$application->run();
