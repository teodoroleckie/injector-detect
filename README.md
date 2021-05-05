# Check insecure request parameters

[![Version](https://poser.pugx.org/tleckie/injector-detect/version)](//packagist.org/packages/tleckie/injector-detect)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/tleckie/injector-detect.svg?style=flat-square)](https://packagist.org/packages/tleckie/injector-detect)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/teodoroleckie/injector-detect/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/teodoroleckie/injector-detect/?branch=main)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/teodoroleckie/injector-detect/badges/code-intelligence.svg?b=main)](https://scrutinizer-ci.com/code-intelligence)
[![Build Status](https://scrutinizer-ci.com/g/teodoroleckie/injector-detect/badges/build.png?b=main)](https://scrutinizer-ci.com/g/teodoroleckie/injector-detect/build-status/main)

## Installation

You can install the package via composer:

```bash
composer require tleckie/injector-detect
```

## Usage
```php
<?php

include_once "vendor/autoload.php";

use Psr\Http\Message\ServerRequestInterface;
use Tleckie\InjectorDetect\Detector;

$detector = new Detector();

/** @var ServerRequestInterface $request*/
$detector->check($request); 

```