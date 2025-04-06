<?php

declare(strict_types=1);

namespace RssClient\Reader;

use Laminas\Feed\Reader\Entry\EntryInterface;
use UnexpectedValueException;

class EntryFactory
{
    /**
     * @param null|iterable<mixed> $feedAuthors
     */
    public function fromFeedEntryAndAuthors(EntryInterface $entry, ?iterable $feedAuthors): Entry
    {
        $pubDate = $entry->getDateModified() ?? $entry->getDateCreated();
        if ($pubDate === null) {
            throw new UnexpectedValueException();
        }

        return new Entry(
            $entry->getTitle(),
            (string) $entry->getDescription(),
            $entry->getLink(),
            $pubDate,
            $this->getEntryCreator($entry->getAuthors(), $feedAuthors)
        );
    }

    /**
     * @param null|iterable<mixed> $entryAuthors
     * @param null|iterable<mixed> $feedAuthors
     */
    private function getEntryCreator(?iterable $entryAuthors, ?iterable $feedAuthors): ?string
    {
        foreach ([$entryAuthors, $feedAuthors] as $authors) {
            if (is_null($authors)) {
                continue;
            }

            $authorsArray = $this->iterableToArray($authors);
            if (empty($authorsArray)) {
                continue;
            }

            return $this->authorsArrayToString($authorsArray);
        }

        return null;
    }

    /**
     * @param array<mixed> $authors
     */
    private function authorsArrayToString(array $authors): string
    {
        return implode(' ', array_map($this->authorAsString(...), $authors));
    }

    private function authorAsString(mixed $author): string
    {
        if (!is_array($author)) {
            return '';
        }

        $parts = [];
        if (array_key_exists('email', $author)) {
            $parts[] = $author['email'];
        }
        if (array_key_exists('name', $author)) {
            $parts[] = $author['name'];
        }
        if (empty($parts)) {
            return '';
        }

        return implode(' ', $parts);
    }

    /**
     * @param null|iterable<mixed> $authors
     *
     * @return array<mixed>
     */
    private function iterableToArray(?iterable $authors): array
    {
        if ($authors === null) {
            return [];
        }
        if (is_array($authors)) {
            return $authors;
        }

        return iterator_to_array($authors);
    }
}
