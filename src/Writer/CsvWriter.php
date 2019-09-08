<?php

declare(strict_types=1);

namespace PawelSlowikRekrutacjaHRtec\RssReader\Writer;

interface CsvWriter
{

    public function write(string $outputFileName, string $outputData);

}
