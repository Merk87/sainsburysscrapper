#Sainsbury's software developer test

This is my solution to sainsbury's software developer test.

#Requirements
* PHP v5.6
* Composer

#Dependencies
* Symfony Console.
* Guzzle PHP HTTP Client
* Symfony DOM-Crawler
* Symfony CSS-Selector

To run the project first run `composer install` to get all the dependencies.

#Commands

In order to run the scrapper you should execute the next command in the root folder of the project:
    * `php app/console sainsburys:scrapper:run` the command support an website argument (thinking in possible future updates)

The test can be run executing `phpunit` in the project's root folder.

#Author notes.
For be able to complete satisfactory the test, I just pick a combo of tools that are widely use for scrapping;
Guzzle HTTP Client in order to manage the request and download of the site to scrap.
Symfony DOM-Crawler in conjunction with Symfony CSS-Selector to make easy the search and selection of the
different elements in the downloaded content.

And last, but not less, Symfony Console that allow us to create beautiful and powerful CLI Applications.

All the test was designed thinking in completing the test to success but also I left open a small window, just in case
I will want to use this as base for any fun tool.

