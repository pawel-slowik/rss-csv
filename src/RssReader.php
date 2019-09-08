<?php

declare(strict_types=1);

namespace PawelSlowikRekrutacjaHRtec\RssReader;

use PawelSlowikRekrutacjaHRtec\RssReader\Writer\CsvWriter;

class RssReader
{

    protected $writer;

    public function __construct(CsvWriter $writer)
    {
        $this->writer = $writer;
    }

    public function read(string $url, string $path)
    {
        $input = $this->fetch($url);
        $converted = $this->convert($input);
        $this->write($path, $converted);
    }

    protected function fetch(string $url)
    {
        echo "fetch $url" . PHP_EOL;
        return "<asd>test</asd>";
    }

    protected function convert(string $data)
    {
        $converted = strip_tags($data);
        echo "convert $data to $converted" . PHP_EOL;
        return $converted;
    }

    protected function write(string $path, string $data)
    {
        $this->writer->write($path, $data);
    }

}
