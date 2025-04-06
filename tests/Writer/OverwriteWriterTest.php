<?php

declare(strict_types=1);

namespace RssClient\Test\Writer;

use RssClient\Exception\RuntimeException;
use RssClient\Writer\Output;
use RssClient\Writer\OverwriteWriter;

/**
 * @covers \RssClient\Writer\OverwriteWriter
 */
class OverwriteWriterTest extends WriterTestBase
{
    private OverwriteWriter $writer;

    private Output $newOutput;

    private Output $overwrittenOutput;

    protected function setUp(): void
    {
        $this->writer = new OverwriteWriter();

        $this->newOutput = new Output("test1\n", "test2\n");

        $this->overwrittenOutput = new Output("header\n", "data\n");

        parent::setUp();
    }

    public function testOutputCreated(): void
    {
        $this->assertFileDoesNotExist($this->tmpFilename);

        $this->writer->write($this->tmpFilename, $this->newOutput);

        $this->assertFileExists($this->tmpFilename);
    }

    public function testNewContent(): void
    {
        $expected = "test1\ntest2\n";

        $this->writer->write($this->tmpFilename, $this->newOutput);

        $actual = file_get_contents($this->tmpFilename);
        $this->assertSame($expected, $actual);
    }

    public function testOverwrittenContent(): void
    {
        $expected = "test1\ntest2\n";

        $this->writer->write($this->tmpFilename, $this->overwrittenOutput);
        $this->writer->write($this->tmpFilename, $this->newOutput);

        $actual = file_get_contents($this->tmpFilename);
        $this->assertSame($expected, $actual);
    }

    public function testException(): void
    {
        $this->expectException(RuntimeException::class);

        @$this->writer->write('/', $this->newOutput);
    }
}
