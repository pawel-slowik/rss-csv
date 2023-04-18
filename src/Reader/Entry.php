<?php

declare(strict_types=1);

namespace RssClient\Reader;

use DateTime;

class Entry
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly string $link,
        public readonly DateTime $pubDate,
        public readonly ?string $creator,
    ) {
    }
}
