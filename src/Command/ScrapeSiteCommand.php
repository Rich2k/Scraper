<?php
namespace Scraper\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;
use GuzzleHttp\Client;

use Scraper\Scrapers\WordpressScraper;

class ScrapeSiteCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:scrape-site')

            // the short description shown while running "php bin/console list"
            ->setDescription('Scrapes the given site and outputs a raw json file')

            // configure arguments
            ->addArgument('url', InputArgument::REQUIRED, 'The Wordpress url to scrape.')
            ->addArgument('category', InputArgument::OPTIONAL, 'The category to match on.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');
        $category = $input->getArgument('category');

        $scraper = new WordpressScraper(new Client(), new Crawler());
        $articles = $scraper->getArticlesMatchingCategory($url, $category);

        $fileSystem = new Filesystem();
        $fileSystem->dumpFile('articles.json', \GuzzleHttp\json_encode($articles));
    }
}