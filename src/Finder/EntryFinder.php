<?php
namespace Scraper\Finder;

use GuzzleHttp\Client;
use Scraper\Model\Article;
use Symfony\Component\DomCrawler\Crawler;

class EntryFinder
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
     * @var string
     */
    private $category;

    /**
     * @var float
     */
    private $totalSize;

    /**
     * @var array
     */
    private $articles;

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
        $this->totalSize = 0;
    }

    /**
     * Get the total file size of all matching articles
     *
     * @return float
     */
    public function getTotalFileSize() : float
    {
        return $this->totalSize;
    }

    /**
     * Get entries
     *
     * @param string $category
     * @return array
     */
    public function getEntries(string $category = null) : array
    {
        $this->articles = [];
        $this->category = $category;
        $this->crawler->filter('article')->each(function (Crawler $node, $i) {
            $links = $node->filter('h2[itemprop="headline"] a');
            $article = new Article();
            $article->setUrl($links->first()->attr('href'));
            $article->setLink($links->first()->text());

            $filteredCategory = true;

            if ($this->category) {
                $categoryLink = $node->filter('a[rel="tag"]');
                if (strtolower($categoryLink->first()->text()) !== $this->category) {
                    $filteredCategory = false;
                }
            }

            if ($filteredCategory === true) {
                $linkResponse = $this->client->request('GET', $article->getUrl());
                $linkResponseBody = (string)$linkResponse->getBody();

                $doc = new \DOMDocument();
                @$doc->loadHTML($linkResponseBody);
                $metas = $doc->getElementsByTagName('meta');

                for ($i = 0; $i < $metas->length; $i++) {
                    $meta = $metas->item($i);
                    if ($meta->getAttribute('name') == 'description') {
                        $article->setMetaDescription($meta->getAttribute('content'));
                    } else if ($meta->getAttribute('property') == 'og:description') {
                        $article->setMetaDescription($meta->getAttribute('content'));
                    } else if ($meta->getAttribute('name') == 'keywords') {
                        $article->setMetaKeywords($meta->getAttribute('content'));
                    }
                }
                $this->totalSize += strlen($linkResponseBody);
                $article->setFileSize(strlen($linkResponseBody));
                $this->articles[] = ['url' => $article->getUrl(), 'link' => $article->getLink(), 'meta description' => $article->getMetaDescription(), 'keywords' => $article->getMetaKeywords(), 'filesize' => $article->getFileSizeText()];

            }
        });
        return $this->articles;
    }
}