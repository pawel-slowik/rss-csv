<?php

declare(strict_types=1);

namespace PawelSlowikRekrutacjaHRtec\RssReader\Writer;

interface WriterInterface
{
    public function write(string $outputFileName, string $outputHeader, string $outputData): void;
}
