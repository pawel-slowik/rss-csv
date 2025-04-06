<?php

declare(strict_types=1);

namespace RssClient\Test;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RssClient\Converter\ConverterInterface;
use RssClient\Reader\ReaderInterface;
use RssClient\RssClient;
use RssClient\Writer\WriterInterface;

/**
 * @covers \RssClient\RssClient
 */
class RssClientTest extends TestCase
{
    private RssClient $rssClient;

    private ReaderInterface&MockObject $reader;

    private WriterInterface&MockObject $writer;

    private ConverterInterface&MockObject $converter;

    protected function setUp(): void
    {
        $this->reader = $this->createMock(ReaderInterface::class);
        $this->writer = $this->createMock(WriterInterface::class);
        $this->converter = $this->createMock(ConverterInterface::class);

        $this->rssClient = new RssClient($this->reader, $this->writer, $this->converter);
    }

    public function testCallsDependencies(): void
    {
        $this->reader
            ->expects($this->once())
            ->method('fetchIter');

        $this->writer
            ->expects($this->once())
            ->method('write');

        $this->converter
            ->expects($this->atLeastOnce())
            ->method('convert');

        $this->rssClient->readAndSave('', '');
    }
}
