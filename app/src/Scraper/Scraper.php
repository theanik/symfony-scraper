<?php

namespace App\Scraper;

use App\Entity\Article;
use App\Message\ScrapDataBroker;
use App\Scraper\Source\Contracts\SourceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\WebDriverBy;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Panther\Client;

class Scraper
{
    public Client $client;

    public MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->client = Client::createChromeClient(__DIR__.'/../../drivers/chromedriver', [
            '--headless',
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--remote-debugging-port=9100'
        ]);

        $this->bus = $bus;
    }

    /**
     * @throws NoSuchElementException
     * @throws TimeoutException
     */
    public function scrap(SourceInterface $source): bool
    {
        $crawler = $this->client->request('GET', $source->getUrl());
        $this->client->waitFor('#latest-stories > div > div > div.WSJTheme--SimplePaginator__center--1zmFPX8Z > div > div > span');
        $totalPagination = $this->getTotalPaginationPage($crawler->filter($source->getTotalPaginatorSelector())->text());
        for ($i = $totalPagination; $i > 0 ; $i--) {
            $collection = [];
            $crawler = $this->client->request('GET', $source->getUrl($i));
            $this->client->waitFor('#latest-stories > div > div > div.WSJTheme--SimplePaginator__center--1zmFPX8Z > div > div > span');
            $crawler->filter($source->getWrapperSelector())->each(function (Crawler $c) use ($source, &$collection) {
                try {
                    $title = $c->filter($source->getTitleSelector())->text();
                    $dateTime = $c->filter($source->getDateSElector())->text();
                    $description = $c->filter($source->getDescSelector())->text();

                    $imagePath = $c->filter($source->getImageSelector())->attr('src');

                    if ($dateTime) {
                        $dateTime =  $this->cleanupDate($dateTime);
                    } else {
                        $dateTime = new \DateTime();
                    }

                    $article = [
                        'title' => $title,
                        'dateTime' => $dateTime,
                        'description' => $description,
                        'image' => $imagePath,
                    ];
                    $collection[] = $article;
                } catch (\Exception $exception) {
                    // TODO: log for exception
                }
            });
            $this->bus->dispatch(new ScrapDataBroker($collection));
        }
        return true;
    }

    /**
     * In order to make DateTime work, we need to clean up the input.
     *
     * @throws \Exception
     */
    private function cleanupDate(string $dateTime): \DateTime
    {
        return \DateTime::createFromFormat("F d, Y", $dateTime);
    }

    private function getTotalPaginationPage(string $paginationText): int
    {
        return explode(' ', $paginationText)[1] ?? 0;
    }

    public function __destruct()
    {
        // $this->client->quit();
        // $this->client->close();
    }
}