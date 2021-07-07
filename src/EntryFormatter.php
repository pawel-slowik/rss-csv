<?php

declare(strict_types=1);

namespace RssClient;

class EntryFormatter
{
    protected $writer;

    // http://www.icu-project.org/apiref/icu4c/classSimpleDateFormat.html#details
    protected $dateFormat = 'd MMMM Y H:mm:ss';

    protected $dateLocale = 'pl_PL';

    protected $purifier;

    public function __construct()
    {
        $purifierConfig = \HTMLPurifier_Config::createDefault();
        $purifierConfig->set('HTML.Allowed', '');
        $this->purifier = new \HTMLPurifier($purifierConfig);
    }

    public function format(array $entry): array
    {
        $entry['pubDate'] = $this->formatDate($entry['pubDate']);
        $entry['description'] = $this->formatDescription($entry['description']);

        return $entry;
    }

    public function formatDate(object $date): string
    {
        return \IntlDateFormatter::formatObject($date, $this->dateFormat, $this->dateLocale);
    }

    public function formatDescription(string $description): string
    {
        // strip HTML
        $clean = $this->purifier->purify($description);

        // strip URLs - http://urlregex.com/
        $urlRegexp = '%(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?%iu';
        $stripped = preg_replace($urlRegexp, '', $clean);

        return $stripped;
    }
}
