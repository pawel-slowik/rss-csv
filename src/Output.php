<?php

declare(strict_types=1);

namespace RssClient;

class Output
{
    private $header;

    private $data;

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
