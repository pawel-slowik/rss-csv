<?php

declare(strict_types=1);

namespace RssClient\Test\Writer;

use RssClient\Exception\RuntimeException;
use RssClient\Writer\AppendWriter;
use RssClient\Writer\Output;
use RssClient\Writer\OverwriteWriter;

/**
 * @covers \RssClient\Writer\AppendWriter
 */
class AppendWriterTest extends WriterTestBase
{
    private AppendWriter $writer;

    private OverwriteWriter $overwriteWriter;

    private Output $previousOutput;

    private Output $appendedOutput;

    protected function setUp(): void
    {
        $this->writer = new AppendWriter();
        $this->overwriteWriter = new OverwriteWriter();

        $this->previousOutput = new Output("header\n", "data\n");

        $this->appendedOutput = new Output("test1\n", "test2\n");

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

        @$this->writer->write('/', $this->appendedOutput);
    }
}
