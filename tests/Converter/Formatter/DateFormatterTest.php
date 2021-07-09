<?php

declare(strict_types=1);

namespace RssClient\Converter\Formatter;

use PHPUnit\Framework\TestCase;

/**
 * @covers \RssClient\Converter\Formatter\DateFormatter
 */
class DateFormatterTest extends TestCase
{
    public function testDateFormat(): void
    {
        $formatter = new DateFormatter();
        $testDateTime = (new \DateTime())->setDate(2018, 10, 16)->setTime(15, 31, 33);
        $actual = $formatter->format($testDateTime);
        $expected = '16 października 2018 15:31:33';
        $this->assertSame($actual, $expected);
    }
}
