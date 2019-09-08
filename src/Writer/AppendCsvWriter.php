<?php

declare(strict_types=1);

namespace PawelSlowikRekrutacjaHRtec\RssReader\Writer;

use PawelSlowikRekrutacjaHRtec\RssReader\Writer\CsvWriter;

class AppendCsvWriter implements CsvWriter
{

    public function write(string $outputFileName, string $outputData)
    {
        echo "append to $outputFileName" . PHP_EOL;
    }

}
