<?php

declare(strict_types=1);

namespace RssClient\Writer;

class Output
{
    public function __construct(
        public readonly string $header,
        public readonly string $data,
    ) {
    }
}
