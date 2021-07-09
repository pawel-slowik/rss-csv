<?php

declare(strict_types=1);

namespace RssClient\Writer;

use RssClient\Exception\RuntimeException;

/**
 * @covers \RssClient\Writer\AppendWriter
 */
class AppendWriterTest extends WriterTestBase
{
    private $writer;

    private $overwriteWriter;

    private $previousOutput;

    private $appendedOutput;

    protected function setUp(): void
    {
        $this->writer = new AppendWriter();
        $this->overwriteWriter = new OverwriteWriter();

        $this->previousOutput = $this->createStub(Output::class);
        $this->previousOutput
            ->method('getHeader')
            ->willReturn("header\n");
        $this->previousOutput
            ->method('getData')
            ->willReturn("data\n");

        $this->appendedOutput = $this->createStub(Output::class);
        $this->appendedOutput
            ->method('getHeader')
            ->willReturn("test1\n");
        $this->appendedOutput
            ->method('getData')
            ->willReturn("test2\n");

        parent::setUp();
    }

    public function testOutputCreated(): void
    {
        $this->assertFileDoesNotExist($this->tmpFilename);

        $this->writer->write($this->tmpFilename, $this->appendedOutput);

        $this->assertFileExists($this->tmpFilename);
    }

    public function testAppendedContent(): void
    {
        $expected = "header\ndata\ntest2\n";
        $this->overwriteWriter->write($this->tmpFilename, $this->previousOutput);

        $this->writer->write($this->tmpFilename, $this->appendedOutput);

        $actual = file_get_contents($this->tmpFilename);
        $this->assertSame($expected, $actual);
    }

    public function testException(): void
    {
        $this->expectException(RuntimeException::class);

        @$this->writer->write('', $this->appendedOutput);
    }
}
