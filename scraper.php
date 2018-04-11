#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Scraper\Command\ScrapeSiteCommand;

$application = new Application();

// ... register commands
$application->add(new ScrapeSiteCommand());

$application->run();
