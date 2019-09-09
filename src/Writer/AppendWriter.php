<?php

declare(strict_types=1);

namespace PawelSlowikRekrutacjaHRtec\RssReader\Writer;

use PawelSlowikRekrutacjaHRtec\RssReader\Exception\RuntimeException;

class AppendWriter implements WriterInterface
{
    public function write(string $outputFileName, string $outputHeader, string $outputData): void
    {
        // skip the header when appending
        if (file_put_contents($outputFileName, $outputData, FILE_APPEND) === false) {
            throw new RuntimeException("can't save to output file: $outputFileName");
        }
    }
}
