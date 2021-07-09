<?php

declare(strict_types=1);

namespace RssClient\Reader;

use Laminas\Feed\Reader\Entry\EntryInterface;

class EntryFactory
{
    public function fromFeedEntryAndAuthors(EntryInterface $entry, ?iterable $feedAuthors): Entry
    {
        return new Entry(
            $entry->getTitle(),
            $entry->getDescription(),
            $entry->getLink(),
            $entry->getDateModified(),
            $this->getEntryCreator($entry->getAuthors(), $feedAuthors)
        );
    }

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

    private function authorsArrayToString(array $authors): string
    {
        return implode(' ', array_map(
            function ($author) {
                return $author['email'] . ' ' . $author['name'];
            },
            $authors
        ));
    }

    private function iterableToArray(?iterable $authors): array
    {
        if (is_array($authors)) {
            return $authors;
        }

        return iterator_to_array($authors);
    }
}
