<?php

declare(strict_types=1);

namespace RssClient\Converter;

use RssClient\Writer\Output;

interface ConverterInterface
{
    public function convert(iterable $entries): Output;
}
