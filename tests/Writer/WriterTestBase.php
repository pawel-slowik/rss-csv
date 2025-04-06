<?php

declare(strict_types=1);

namespace RssClient\Test\Writer;

use PHPUnit\Framework\TestCase;

class WriterTestBase extends TestCase
{
    protected string $tmpFilename;

    protected function setUp(): void
    {
        $this->tmpFilename = tempnam(sys_get_temp_dir(), __CLASS__);
        unlink($this->tmpFilename);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tmpFilename)) {
            unlink($this->tmpFilename);
        }
    }
}
