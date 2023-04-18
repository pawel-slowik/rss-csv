<?php

declare(strict_types=1);

namespace RssClient\Converter\Formatter;

use DateTimeInterface;
use IntlDateFormatter;

class DateFormatter
{
    // http://www.icu-project.org/apiref/icu4c/classSimpleDateFormat.html#details
    private const FORMAT = 'd MMMM Y H:mm:ss';

    private const LOCALE = 'pl_PL';

    public function format(DateTimeInterface $date): string
    {
        return IntlDateFormatter::formatObject($date, self::FORMAT, self::LOCALE);
    }
}
