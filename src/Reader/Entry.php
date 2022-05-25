<?php

declare(strict_types=1);

namespace RssClient\Reader;

use DateTime;

class Entry
{
    private string $title;

    private string $description;

    private string $link;

    private DateTime $pubDate;

    private ?string $creator;

    public function __construct(
        string $title,
        string $description,
        string $link,
        DateTime $pubDate,
        ?string $creator
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->link = $link;
        $this->pubDate = $pubDate;
        $this->creator = $creator;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function getPubDate(): DateTime
    {
        return $this->pubDate;
    }

    public function getCreator(): ?string
    {
        return $this->creator;
    }
}
