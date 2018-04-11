<?php
namespace Test\Scraper\Finder;

use PHPUnit\Framework\TestCase;
use Scraper\Finder\EntryFinder;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class EntryFinderTest extends TestCase
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Crawler
     */
    private $crawler;

    public function testCategoryFilter()
    {
        $this->setUpMocks();
        $entryFinder = new EntryFinder($this->client, $this->crawler);
        $entries = $entryFinder->getEntries('digitalia');

        $this->assertEquals(1, sizeof($entries));
    }

    public function testNegativeCategoryFilter()
    {
        $this->setUpMocks();
        $entryFinder = new EntryFinder($this->client, $this->crawler);
        $entries = $entryFinder->getEntries('photos');

        $this->assertEquals(0, sizeof($entries));
    }

    public function testArticleResponse()
    {
        $this->setUpMocks();
        $entryFinder = new EntryFinder($this->client, $this->crawler);
        $entries = $entryFinder->getEntries('digitalia');

        $entry = $entries[0];

        $this->assertEquals('https://www.black-ink.org/2018/03/links-for-tuesday-march-6th-2018/', $entry['url']);
        $this->assertEquals('Links for Tuesday March 6th 2018', $entry['link']);
        $this->assertEquals('How to Rands – Rands in Repose This really isn\'t a bad guide to basic tech management – making sure your team know what the score is regarding their line manager, and what he\'…', $entry['meta description']);
        $this->assertNull($entry['keywords']);
        $this->assertEquals('355kb', $entry['filesize']);
    }

    private function setUpMocks()
    {
        $mock = new MockHandler([
            new Response(200, [], '<html><body><article id="post-8592" class="entry post publish author-admin post-8592 format-standard category-workblog" itemscope="itemscope" itemtype="http://schema.org/BlogPosting" itemprop="blogPost"><div class="entry-wrap">
<header class="entry-header">	<h2 class="entry-title" itemprop="headline"><a href="https://www.black-ink.org/2018/03/links-for-tuesday-march-6th-2018/" rel="bookmark">Links for Tuesday March 6th 2018</a></h2>
<div class="entry-meta">
<time class="entry-time" datetime="2018-03-06T18:00:00+00:00" itemprop="datePublished" title="Tuesday, March 6, 2018, 6:00 pm">March 6, 2018</time>
<span class="entry-comments-link"> / <a href="https://www.black-ink.org/2018/03/links-for-tuesday-march-6th-2018/#respond">Leave a Comment</a></span>	</div><!-- .entry-meta --></header><!-- .entry-header -->		
<div class="entry-summary" itemprop="description">
<ul>
<li><a href="http://randsinrepose.com/archives/how-to-rands/">How to Rands &ndash; Rands in Repose</a> <br /> This really isn&#039;t a bad guide to basic tech management &#8211; making sure your team know what the score is regarding their line manager, and what he&#039;s about.</li>
</ul>

</div>
<footer class="entry-footer"><div class="entry-meta">
	<span class="entry-terms category" itemprop="articleSection">Posted in: <a href="https://www.black-ink.org/category/workblog/" rel="tag">Digitalia</a></span>			
</div></footer></div></article></body></html>'),
            new Response(200, [], '
<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8">
<meta property="og:description" content="How to Rands &ndash; Rands in Repose This really isn&#039;t a bad guide to basic tech management &#8211; making sure your team know what the score is regarding their line manager, and what he&#039;…" />
</head>
<body>
Hello World
</body>
</html>')
        ]);

        $handler = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handler]);

        $body = (string)$this->client->request('GET', '/')->getBody();
        $this->crawler = new Crawler();
        $this->crawler->addContent($body);
    }
}