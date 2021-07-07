<?php

declare(strict_types=1);

namespace RssClient\Writer;

use RssClient\Exception\RuntimeException;

class AppendWriter implements WriterInterface
{
    public function write(// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
        string $outputFileName,
        string $outputHeader,
        string $outputData
    ): void {
        // skip the header when appending
        if (file_put_contents($outputFileName, $outputData, FILE_APPEND) === false) {
            throw new RuntimeException("can't save to output file: ${outputFileName}");
        }
    }
}
