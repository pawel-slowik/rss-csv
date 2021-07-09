<?php

declare(strict_types=1);

namespace RssClient\Writer;

use RssClient\Exception\RuntimeException;

class AppendWriter implements WriterInterface
{
    public function write(string $outputFileName, Output $output): void
    {
        // skip the header when appending
        if (file_put_contents($outputFileName, $output->getData(), FILE_APPEND) === false) {
            throw new RuntimeException("can't save to output file: ${outputFileName}");
        }
    }
}
