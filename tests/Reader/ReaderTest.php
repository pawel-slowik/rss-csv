<?php

declare(strict_types=1);

namespace RssClient\Reader;

use Laminas\Feed\Reader\Http\ClientInterface as LaminasHttpClientInterface;
use Laminas\Feed\Reader\Http\ResponseInterface as LaminasHttpResponseInterface;
use Laminas\Feed\Reader\Reader as LaminasReader;
use PHPUnit\Framework\TestCase;

/**
 * @covers \RssClient\Reader\Reader
 */
class ReaderTest extends TestCase
{
    private const FEED_URL = 'https://www.example.com/rss.xml';

    private const FEED_CONTENT = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<rss
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:atom="http://www.w3.org/2005/Atom"
    version="2.0"
    xmlns:media="http://search.yahoo.com/mrss/"
>
    <channel>
        <title><![CDATA[foo]]></title>
        <description><![CDATA[bar]]></description>
        <link>https://www.example.com/</link>
        <item>
            <title><![CDATA[baz1]]></title>
            <description><![CDATA[baz2]]></description>
            <link>https://www.example.com/news/baz/</link>
            <pubDate>Fri, 09 Jul 2021 16:56:40 GMT</pubDate>
        </item>
        <item>
            <title><![CDATA[qux1]]></title>
            <description><![CDATA[qux2]]></description>
            <link>https://www.example.com/news/qux/</link>
            <pubDate>Fri, 09 Jul 2021 17:55:39 GMT</pubDate>
        </item>
    </channel>
</rss>
XML;

    private Reader $reader;

    protected function setUp(): void
    {
        // ugly, but I can't think of another way to test the static call

        $response = $this->createStub(LaminasHttpResponseInterface::class);
        $response
            ->method('getStatusCode')
            ->willReturn(200);
        $response
            ->method('getBody')
            ->willReturn(self::FEED_CONTENT);

        $client = $this->createStub(LaminasHttpClientInterface::class);
        $client
            ->method('get')
            ->willReturnMap([[self::FEED_URL, $response]]);

        LaminasReader::setHttpClient($client);

        $this->reader = new Reader($this->createStub(EntryFactory::class));
    }

    public function testReturnsEntries(): void
    {
        $entries = $this->reader->fetchIter(self::FEED_URL);

        $this->assertContainsOnlyInstancesOf(Entry::class, $entries);
    }

    public function testCount(): void
    {
        $entries = $this->reader->fetchIter(self::FEED_URL);

        $this->assertCount(2, $entries);
    }
}
