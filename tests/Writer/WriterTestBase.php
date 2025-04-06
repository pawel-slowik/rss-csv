<?php

declare(strict_types=1);

namespace RssClient\Test\Writer;

use LogicException;
use PHPUnit\Framework\TestCase;

class WriterTestBase extends TestCase
{
    protected string $tmpFilename;

    protected function setUp(): void
    {
        $tmpFilename = tempnam(sys_get_temp_dir(), __CLASS__) or throw new LogicException();
        $this->tmpFilename = $tmpFilename;
        unlink($this->tmpFilename);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tmpFilename)) {
            unlink($this->tmpFilename);
        }
    }
}
