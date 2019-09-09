<?php

declare(strict_types=1);

namespace PawelSlowikRekrutacjaHRtec\RssReader\Writer;

use PawelSlowikRekrutacjaHRtec\RssReader\Writer\CsvWriter;
use PawelSlowikRekrutacjaHRtec\RssReader\Exception\RuntimeException;

class OverwriteCsvWriter implements CsvWriter
{

    public function write(string $outputFileName, string $outputHeader, string $outputData)
    {
        if (file_put_contents($outputFileName, $outputHeader . $outputData) === false) {
            throw new RuntimeException("can't save to output file: $outputFileName");
        }
    }

}
