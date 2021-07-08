<?php

declare(strict_types=1);

namespace RssClient;

use DateTime;

class Entry
{
    private $title;

    private $description;

    private $link;

    private $pubDate;

    private $creator;

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