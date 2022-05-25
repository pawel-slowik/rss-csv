<?php

declare(strict_types=1);

namespace RssClient;

use RssClient\Converter\ConverterInterface;
use RssClient\Reader\ReaderInterface;
use RssClient\Writer\WriterInterface;

class RssClient
{
    private ReaderInterface $reader;

    private WriterInterface $writer;

    private ConverterInterface $converter;

    public function __construct(
        ReaderInterface $reader,
        WriterInterface $writer,
        ConverterInterface $converter
    ) {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->converter = $converter;
    }

    public function readAndSave(string $inputUrl, string $outputFilename): void
    {
        $input = $this->reader->fetchIter($inputUrl);
        $output = $this->converter->convert($input);
        $this->writer->write($outputFilename, $output);
    }
}
