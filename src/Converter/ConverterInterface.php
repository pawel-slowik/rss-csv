<?php

declare(strict_types=1);

namespace RssClient\Converter;

use RssClient\Reader\Entry;
use RssClient\Writer\Output;

interface ConverterInterface
{
    /**
     * @param iterable<Entry> $entries
     */
    public function convert(iterable $entries): Output;
}
