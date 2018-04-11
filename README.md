# Wordpress Article Scraper

## Requirements

 - PHP 7.1
 - Symfony 4 Console Application, installed via Composer

## Installing

 - Download or clone this repo `git clone https://github.com/Rich2k/Scraper`
 - Install the dependencies `composer install`

If you do not have composer it can be downloaded here: https://getcomposer.org/download/

If you do not have PHP 7.1. installed locally you can use Homebrew on macOS

https://brew.sh/
https://developerjack.com/blog/2016/installing-php71-with-homebrew/

## Running

 - Change directory into the downloaded repository `cd scraper`
 - Execute the scrape command `php ./scraper.php app:scrape-site [URL] [CATEGORY_DISPLAY_NAME]`
 
 
 [URL] is the page you want the scraper to start on.
 [CATEGORY_DISPLAY_NAME] is a display name for a category you want to filter the articles by.
 
 For example
 
 ```
php ./scraper.php app:scrape-site  http://www.black-ink.org digitalia
```

## Tests

The code has been written to be fully testable taking into account Dependency Injection.  

Due to time constraints there isn't 100% code coverage that I would normally like to apply.

The tests can be run with 

`php vendor/bin/phpunit test`