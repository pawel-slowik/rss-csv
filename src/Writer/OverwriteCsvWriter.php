<?php

declare(strict_types=1);

namespace PawelSlowikRekrutacjaHRtec\RssReader\Writer;

use PawelSlowikRekrutacjaHRtec\RssReader\Writer\CsvWriter;

class OverwriteCsvWriter implements CsvWriter
{

    public function write(string $outputFileName, string $outputData)
    {
        echo "overwrite $outputFileName" . PHP_EOL;
    }

}
