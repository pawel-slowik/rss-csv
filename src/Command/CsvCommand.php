<?php

declare(strict_types=1);

namespace RssReader\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use RssReader\RssReader;

class CsvCommand extends Command
{
    protected $reader;

    public function __construct(RssReader $reader, string $name, string $description)
    {
        parent::__construct();
        $this->reader = $reader;
        $this->setName($name);
        $this->setDescription($description);
        $this->addArgument('url', InputArgument::REQUIRED, 'Input RSS URL');
        $this->addArgument('path', InputArgument::REQUIRED, 'Output CSV file name');
    }

    protected function execute(// phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter
        InputInterface $input,
        OutputInterface $output
    ): void {
        $url = $input->getArgument('url');
        $path = $input->getArgument('path');
        $this->reader->read($url, $path);
    }
}
