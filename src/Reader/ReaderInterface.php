<?php

declare(strict_types=1);

namespace RssClient\Reader;

interface ReaderInterface
{
    /**
     * @return iterable<Entry>
     */
    public function fetchIter(string $url): iterable;
}
