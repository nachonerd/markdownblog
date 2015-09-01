Markdown Blog
===============

[![Build Status](https://travis-ci.org/nachonerd/markdownblog.svg?branch=development)](https://travis-ci.org/nachonerd/markdownblog) [![Code Climate](https://codeclimate.com/github/nachonerd/markdownblog/badges/gpa.svg)](https://codeclimate.com/github/nachonerd/markdownblog) [![Test Coverage](https://codeclimate.com/github/nachonerd/markdownblog/badges/coverage.svg)](https://codeclimate.com/github/nachonerd/markdownblog/coverage)

### Description
Minimalist Markdown Blog Framework based on Silex

### License
GNU GPL v3

### Requirements
- [PHP version 5.4](http://php.net/releases/5_4_0.php)
- [Composer](https://getcomposer.org/)
- [SILEX](http://silex.sensiolabs.org/)
- [cebe/markdown](http://markdown.cebe.cc/)
- [PHP Unit 4.7.x](https://phpunit.de/) (optional)
- [PHP_CodeSniffer 2.x](http://pear.php.net/package/PHP_CodeSniffer/redirected) (optional)

### Current Version
__0.0.2__

### Changelog

__0.0.x__
- Automatic add pages following config files.
- Init Enviroment of Testing

__0.0.0__
- Start Project

### Get Starting

```
$ git clone git@github.com:nachonerd/markdownblog.git
$ cd markdownblog
$ composer install
```

### Running Test Suite

```
$ cd markdownblog
$ vendor/bin/phpunit -c tests/phpunit.xml
```
