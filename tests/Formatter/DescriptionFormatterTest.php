<?php

declare(strict_types=1);

namespace RssClient\Formatter;

use PHPUnit\Framework\TestCase;

/**
 * @covers \RssClient\Formatter\DescriptionFormatter
 */
class DescriptionFormatterTest extends TestCase
{
    private $formatter;

    protected function setUp(): void
    {
        $this->formatter = new DescriptionFormatter();
    }

    public function testDescriptionLink(): void
    {
        $input = 'foo <a href="asd">asd</a> bar';
        $actual = $this->formatter->format($input);
        $expected = 'foo asd bar';
        $this->assertSame($actual, $expected);
    }

    public function testDescriptionSimpleXss(): void
    {
        $input = '<script>window.onload = function() {var AllLinks=document.getElementsByTagName("a"); 
AllLinks[0].href = "http://badexample.com/malicious.exe"; }</script>';
        $actual = $this->formatter->format($input);
        $expected = '';
        $this->assertSame($actual, $expected);
    }

    public function testDescriptionUrl(): void
    {
        $input = 'foo https://example.com/path/?query#fragment bar';
        $actual = $this->formatter->format($input);
        $expected = 'foo  bar';
        $this->assertSame($actual, $expected);
    }

    public function testDescriptionMultipleUrls(): void
    {
        $input = 'foo http://example.com/ bar https://whatever.test baz';
        $actual = $this->formatter->format($input);
        $expected = 'foo  bar  baz';
        $this->assertSame($actual, $expected);
    }
}
