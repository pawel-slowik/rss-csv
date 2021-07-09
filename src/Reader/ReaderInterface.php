<?php

declare(strict_types=1);

namespace RssClient\Reader;

interface ReaderInterface
{
    public function fetchIter(string $url): iterable;
}
