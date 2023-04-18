<?php

declare(strict_types=1);

namespace RssClient\Reader;

use Laminas\Feed\Reader\Entry\EntryInterface;

class EntryFactory
{
    /**
     * @param null|iterable<array{'email': string, 'name': string}> $feedAuthors
     */
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

    /**
     * @param null|iterable<array{'email': string, 'name': string}> $entryAuthors
     * @param null|iterable<array{'email': string, 'name': string}> $feedAuthors
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
     * @param array<array{'email': string, 'name': string}> $authors
     */
    private function authorsArrayToString(array $authors): string
    {
        return implode(' ', array_map(
            function ($author) {
                return $author['email'] . ' ' . $author['name'];
            },
            $authors
        ));
    }

    /**
     * @param null|iterable<array{'email': string, 'name': string}> $authors
     *
     * @return array<array{'email': string, 'name': string}>
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
