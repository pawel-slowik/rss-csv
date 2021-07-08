<?php

declare(strict_types=1);

namespace RssClient\Writer;

use RssClient\Exception\RuntimeException;

/**
 * @covers \RssClient\Writer\OverwriteWriter
 */
class OverwriteWriterTest extends WriterTestBase
{
    protected $writer;

    protected function setUp(): void
    {
        $this->writer = new OverwriteWriter();
        parent::setUp();
    }

    public function testOutputCreated(): void
    {
        $this->assertFileDoesNotExist($this->tmpFilename);
        $this->writer->write($this->tmpFilename, '', '');
        $this->assertFileExists($this->tmpFilename);
    }

    public function testNewContent(): void
    {
        $expected = "header\ndata\n";
        $this->writer->write($this->tmpFilename, "header\n", "data\n");
        $actual = file_get_contents($this->tmpFilename);
        $this->assertSame($expected, $actual);
    }

    public function testOverwrittenContent(): void
    {
        $expected = "test1\ntest2\n";
        $this->writer->write($this->tmpFilename, "header\n", "data\n");
        $this->writer->write($this->tmpFilename, "test1\n", "test2\n");
        $actual = file_get_contents($this->tmpFilename);
        $this->assertSame($expected, $actual);
    }

    public function testException(): void
    {
        $this->expectException(RuntimeException::class);
        @$this->writer->write('', '', '');
    }
}
