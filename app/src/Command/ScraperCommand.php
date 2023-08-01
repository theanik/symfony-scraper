<?php

namespace App\Command;

use App\Scraper\Scraper;
use App\Scraper\Source\TheWallStreetJournal;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ScraperCommand extends Command
{
    /**
     * @var Scraper
     */
    private Scraper $scraper;

    /**
     * @param Scraper $scraper
     */
    public function __construct(Scraper $scraper)
    {
        parent::__construct();
        $this->scraper = $scraper;
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('start:scrap')
            ->setDescription('Article scraping has been started');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Facebook\WebDriver\Exception\NoSuchElementException
     * @throws \Facebook\WebDriver\Exception\TimeoutException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->scraper->scrap(new TheWallStreetJournal());
        // Return below values according to the occurred situation
        if ($result) {
            // if everything is executed successfully with no issues then return SUCCESS as below
            return Command::SUCCESS;
        } else {
            return Command::FAILURE;
        }
    }

}