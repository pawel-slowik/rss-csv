<?php

declare(strict_types=1);

namespace RssClient\Writer;

use PHPUnit\Framework\TestCase;

/**
 * @covers \RssClient\Writer\Output
 */
class OutputTest extends TestCase
{
    public function testOutput(): void
    {
        $output = new Output('foo', 'bar');

        $this->assertSame('foo', $output->getHeader());
        $this->assertSame('bar', $output->getData());
    }
}
