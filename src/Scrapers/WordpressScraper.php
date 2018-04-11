<?php
namespace Scraper\Scrapers;

use GuzzleHttp\Client;
use Scraper\Finder\EntryFinder;
use Symfony\Component\DomCrawler\Crawler;

class WordpressScraper
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * Constructor
     *
     * @param Client $client
     * @param Crawler $crawler
     */
    public function __construct(Client $client, Crawler $crawler)
    {
        $this->client = $client;
        $this->crawler = $crawler;
    }

    /**
     * Scrape the matching page by url and category
     *
     * @param $url
     * @param $category
     * @return array
     */
    public function getArticlesMatchingCategory(string $url, string $category = null) : array
    {
        $res = $this->client->request('GET', $url);
        $body = $res->getBody();

        $this->crawler->addContent($body);

        $entryFinder = new EntryFinder($this->client, $this->crawler);

        return [
            'results' => $entryFinder->getEntries(strtolower($category)),
            'total' => $entryFinder->getTotalFileSize() . 'Kb'
        ];
    }
}