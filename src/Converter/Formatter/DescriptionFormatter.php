<?php

declare(strict_types=1);

namespace RssClient\Converter\Formatter;

use HTMLPurifier;
use HTMLPurifier_Config;

class DescriptionFormatter
{
    private const PURIFIER_OPTIONS = [
        'HTML.Allowed' => '',
    ];

    // http://urlregex.com/
    private const URL_REGEXP = '%(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?%iu';

    private HTMLPurifier $purifier;

    public function __construct()
    {
        $purifierConfig = HTMLPurifier_Config::createDefault();
        foreach (self::PURIFIER_OPTIONS as $optionName => $optionValue) {
            $purifierConfig->set($optionName, $optionValue);
        }

        $this->purifier = new HTMLPurifier($purifierConfig);
    }

    public function format(string $description): string
    {
        // strip HTML
        $clean = $this->purifier->purify($description);

        // strip URLs
        $stripped = preg_replace(self::URL_REGEXP, '', $clean);

        return $stripped;
    }
}
