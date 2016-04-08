# Audiens/appnexus-client
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Audiens/appnexus-client/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Audiens/appnexus-client/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/Audiens/appnexus-client/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Audiens/appnexus-client/build-status/master)
[![Code Climate](https://codeclimate.com/github/Audiens/appnexus-client/badges/gpa.svg)](https://codeclimate.com/github/Audiens/appnexus-client)
[![Coverage Status](https://coveralls.io/repos/github/Audiens/appnexus-client/badge.svg?branch=master)](https://coveralls.io/github/Audiens/appnexus-client?branch=master)

An OOP implementation af the Appnexus API.
  
## Installation
To use this package, use composer:

 * from CLI: `composer require Audiens/appnexus-client`
 * or, directly in your `composer.json`:

``` 
{
    "require": {
        "Audiens/appnexus-client": "dev-master"
    }
}
```
  
## Usage


```php

require 'vendor/autoload.php';

$username = '{yourUsername}';
$password = '{yourPassword}';
$memberId = '{yourPassword}';

$appnexus = new AppnexusFacade($username, $password, $memberId);

// Segment creation example

$segment = new Segment();
$segment->setName('Male');
$segment->setCategory('Gender');
$segment->setMemberId($memberId);
$segment->setActive(true);

$repositoryResponse = $appnexus->add($segment);

if ($repositoryResponse->isSuccessful()){
 echo "Success!";
}

```

# Cache

The client implement a simple cache service using doctrine/cache.By default is enabled but you can disabled it with:

```php
 
require 'vendor/autoload.php';

$username = '{yourUsername}';
$password = '{yourPassword}';
$memberId = '{yourPassword}';

$appnexus = new AppnexusFacade($username, $password, $memberId);

$appnexus->disableCache();

```