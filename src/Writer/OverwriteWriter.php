<?php

declare(strict_types=1);

namespace RssReader\Writer;

use RssReader\Exception\RuntimeException;

class OverwriteWriter implements WriterInterface
{
    public function write(string $outputFileName, string $outputHeader, string $outputData): void
    {
        if (file_put_contents($outputFileName, $outputHeader . $outputData) === false) {
            throw new RuntimeException("can't save to output file: ${outputFileName}");
        }
    }
}
