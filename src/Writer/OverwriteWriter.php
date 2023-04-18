<?php

declare(strict_types=1);

namespace RssClient\Writer;

use RssClient\Exception\RuntimeException;

class OverwriteWriter implements WriterInterface
{
    public function write(string $outputFileName, Output $output): void
    {
        if (file_put_contents($outputFileName, $output->header . $output->data) === false) {
            throw new RuntimeException("can't save to output file: ${outputFileName}");
        }
    }
}
