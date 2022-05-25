<?php

declare(strict_types=1);

namespace RssClient\Writer;

use RssClient\Exception\RuntimeException;

/**
 * @covers \RssClient\Writer\OverwriteWriter
 */
class OverwriteWriterTest extends WriterTestBase
{
    private $writer;

    private $newOutput;

    private $overwrittenOutput;

    protected function setUp(): void
    {
        $this->writer = new OverwriteWriter();

        $this->newOutput = $this->createStub(Output::class);
        $this->newOutput
            ->method('getHeader')
            ->willReturn("test1\n");
        $this->newOutput
            ->method('getData')
            ->willReturn("test2\n");

        $this->overwrittenOutput = $this->createStub(Output::class);
        $this->overwrittenOutput
            ->method('getHeader')
            ->willReturn("header\n");
        $this->overwrittenOutput
            ->method('getData')
            ->willReturn("data\n");

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
