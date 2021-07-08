<?php

declare(strict_types=1);

namespace RssClient;

use PHPUnit\Framework\TestCase;

/**
 * @covers \RssClient\EntryFormatter
 */
class EntryFormatterDateTest extends TestCase
{
    public function testDateFormat(): void
    {
        $formatter = new EntryFormatter();
        $testDateTime = (new \DateTime())->setDate(2018, 10, 16)->setTime(15, 31, 33);
        $actual = $formatter->formatDate($testDateTime);
        $expected = '16 października 2018 15:31:33';
        $this->assertSame($actual, $expected);
    }
}
