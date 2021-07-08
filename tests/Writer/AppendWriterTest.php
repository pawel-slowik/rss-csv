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

    protected function setUp(): void
    {
        $this->writer = new AppendWriter();
        $this->overwriteWriter = new OverwriteWriter();
        parent::setUp();
    }

    public function testOutputCreated(): void
    {
        $this->assertFileDoesNotExist($this->tmpFilename);
        $this->writer->write($this->tmpFilename, '', '');
        $this->assertFileExists($this->tmpFilename);
    }

    public function testAppendedContent(): void
    {
        $expected = "header\ndata\ntest2\n";
        $this->overwriteWriter->write($this->tmpFilename, "header\n", "data\n");
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
