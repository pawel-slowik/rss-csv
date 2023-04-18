<?php

declare(strict_types=1);

namespace RssClient\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use RssClient\RssClient;

class CsvCommand extends Command
{
    private RssClient $rssClient;

    public function __construct(RssClient $rssClient, string $name, string $description)
    {
        parent::__construct();
        $this->rssClient = $rssClient;
        $this->setName($name);
        $this->setDescription($description);
        $this->addArgument('url', InputArgument::REQUIRED, 'Input RSS URL');
        $this->addArgument('path', InputArgument::REQUIRED, 'Output CSV file name');
    }

    protected function execute(// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
        InputInterface $input,
        OutputInterface $output
    ): int {
        /** @var string $url */
        $url = $input->getArgument('url');
        /** @var string $path */
        $path = $input->getArgument('path');
        $this->rssClient->readAndSave($url, $path);

        return Command::SUCCESS;
    }
}
