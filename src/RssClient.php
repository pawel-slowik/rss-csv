<?php

declare(strict_types=1);

namespace RssClient;

use RssClient\Converter\Converter;
use RssClient\Converter\ConverterInterface;
use RssClient\Reader\ReaderInterface;
use RssClient\Writer\WriterInterface;

class RssClient
{
    private $reader;

    private $writer;

    private $converter;

    public function __construct(
        ReaderInterface $reader,
        WriterInterface $writer
    ) {
        $this->reader = $reader;
        $this->writer = $writer;
        $this->converter = new Converter();
    }

    public function readAndSave(string $inputUrl, string $outputFilename): void
    {
        $input = $this->reader->fetchIter($inputUrl);
        $output = $this->converter->convert($input);
        $this->writer->write($outputFilename, $output->getHeader(), $output->getData());
    }
}
