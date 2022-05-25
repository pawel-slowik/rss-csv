<?php

declare(strict_types=1);

namespace RssClient\Writer;

class Output
{
    private string $header;

    private string $data;

    public function __construct(
        string $header,
        string $data
    ) {
        $this->header = $header;
        $this->data = $data;
    }

    public function getHeader(): string
    {
        return $this->header;
    }

    public function getData(): string
    {
        return $this->data;
    }
}
