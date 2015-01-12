PHPJ
====

[![Build Status](https://travis-ci.org/PHPJ/PHPJ.svg?branch=master)](https://travis-ci.org/PHPJ/PHPJ)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/PHPJ/PHPJ/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/PHPJ/PHPJ/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/PHPJ/PHPJ/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/PHPJ/PHPJ/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/phpj/phpj/v/stable.svg)](https://packagist.org/packages/kozz/email-address-parser)
[![Latest Unstable Version](https://poser.pugx.org/phpj/phpj/v/unstable.svg)](https://packagist.org/packages/kozz/email-address-parser)
[![License](http://img.shields.io/packagist/l/phpj/phpj.svg)](https://packagist.org/packages/kozz/email-address-parser)

PHP OOP Core inspired by Java

Sources

http://docs.oracle.com/javase/8/docs/api/

## Contribute
1. fork & clone
2. ```composer install```
3. ```vendor/bin/phpunit --debug```
4. contribute
5. pull request

## Progress

- Lang
  - Object (Alpha ready)
  - String (Alpha ready)
  - StringBuilder (inited)
  - Number
  - Integer
  - Math (Inited)
  - StrictMath (inited)
- Util
  - Locale (inted)
  - StringJoiner (inited)

## Problems and solutions:
- Method overloading
  Solution: factory method
- Object math operators
  Solutions: 
  1. php5-operator [http://pecl.php.net/package/operator] [http://webreflection.blogspot.ru/2008/06/from-future-php-javascript-like-number.html]
  2. Extending GMP class [http://php.net/manual/en/class.gmp.php]
