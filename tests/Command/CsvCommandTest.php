<?php

declare(strict_types=1);

namespace RssClient\Test\Command;

use RssClient\Command\CsvCommand;
use RssClient\RssClient;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \RssClient\Command\CsvCommand
 */
class CsvCommandTest extends TestCase
{
    private InputInterface $input;

    private OutputInterface $output;

    private RssClient&MockObject $rssClient;

    protected function setUp(): void
    {
        $this->input = $this->createStub(InputInterface::class);
        $this->input
            ->method('getArgument')
            ->willReturn('');

        $this->output = $this->createStub(OutputInterface::class);

        $this->rssClient = $this->createMock(RssClient::class);
    }

    public function testShouldHaveTwoRequiredArguments(): void
    {
        $command = new CsvCommand($this->rssClient, 'test command name', '');

        $this->assertSame(2, $command->getDefinition()->getArgumentRequiredCount());
    }

    public function testShouldHaveName(): void
    {
        $command = new CsvCommand($this->rssClient, 'foo', '');

        $this->assertSame('foo', $command->getName());
    }

    public function testShouldHaveDescription(): void
    {
        $command = new CsvCommand($this->rssClient, 'test command name', 'bar');

        $this->assertSame('bar', $command->getDescription());
    }

    public function testShouldCallRssClient(): void
    {
        $this->rssClient
            ->expects($this->once())
            ->method('readAndSave');

        $command = new CsvCommand($this->rssClient, 'test command name', '');

        $command->run($this->input, $this->output);
    }
}
