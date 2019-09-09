<?php

declare(strict_types=1);

namespace PawelSlowikRekrutacjaHRtec\RssReader;

use Zend\Feed\Reader\Reader as FeedReader;

use PawelSlowikRekrutacjaHRtec\RssReader\Writer\WriterInterface;

class RssReader
{
    protected $writer;

    // http://www.icu-project.org/apiref/icu4c/classSimpleDateFormat.html#details
    protected $dateFormat = 'd MMMM Y H:mm:ss';

    protected $dateLocale = 'pl_PL';

    protected $purifier;

    public function __construct(WriterInterface $writer)
    {
        $this->writer = $writer;
        $purifierConfig = \HTMLPurifier_Config::createDefault();
        $purifierConfig->set('HTML.Allowed', '');
        $this->purifier = new \HTMLPurifier($purifierConfig);
    }

    public function read(string $url, string $path): void
    {
        $input = $this->fetchIter($url);
        $formatted = $this->formatIter($input);
        list($header, $converted) = $this->convert($formatted);
        $this->write($path, $header, $converted);
    }

    protected function fetchIter(string $url): Iterable
    {
        $feed = FeedReader::import($url);
        $feedAuthors = $feed->getAuthors();
        foreach ($feed as $entry) {
            yield [
                'title' => $entry->getTitle(),
                'description' => $entry->getDescription(),
                'link' => $entry->getLink(),
                'pubDate' => $entry->getDateModified(),
                'creator' => $this->getEntryCreator($entry->getAuthors(), $feedAuthors),
            ];
        }
    }

    protected function getEntryCreator($entryAuthors, $feedAuthors): ?string
    {
        if (!$entryAuthors && !$feedAuthors) {
            return null;
        }
        $authors = ($entryAuthors) ? $entryAuthors : $feedAuthors;
        return implode(' ', array_map(
            function ($author) { return $author['email'] . ' ' . $author['name']; },
            iterator_to_array($authors)
        ));
    }

    protected function formatIter(Iterable $entries): Iterable
    {
        foreach ($entries as $entry) {
            yield $this->formatEntry($entry);
        }
    }

    protected function formatEntry(array $entry): array
    {
        $entry['pubDate'] = $this->formatEntryDate($entry['pubDate']);
        $entry['description'] = $this->formatEntryDescription($entry['description']);
        return $entry;
    }

    protected function formatEntryDate(object $date): string
    {
        return \IntlDateFormatter::formatObject($date, $this->dateFormat, $this->dateLocale);
    }

    protected function formatEntryDescription(string $description): string
    {
        // strip HTML
        $clean = $this->purifier->purify($description);

        // strip URLs - http://urlregex.com/
        $urlRegexp = '%(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?%iu';
        $stripped = preg_replace($urlRegexp, '', $clean);

        return $stripped;
    }

    protected function convert(Iterable $data): array
    {
        $csvData = \League\Csv\Writer::createFromString();
        $csvHeader = \League\Csv\Writer::createFromString();
        $headerDone = false;
        foreach ($data as $entry) {
            if (!$headerDone) {
                $csvHeader->insertOne(array_keys($entry));
                $headerDone = true;
            }
            $csvData->insertOne($entry);
        }
        return [$csvHeader->getContent(), $csvData->getContent()];
    }

    protected function write(string $path, string $header, string $data): void
    {
        $this->writer->write($path, $header, $data);
    }
}
