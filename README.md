[![Build Status](https://travis-ci.org/mbeineris/RateLimitBundle.svg?branch=master)](https://travis-ci.org/mbeineris/RateLimitBundle) [![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
# RateLimitBundle

A light-weight symfony bundle that limits request rate for given path.

Requirements
============

Redis server

Installation
============

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require mabe/ratelimit-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Mabe\RateLimitBundle\MabeRateLimitBundle(),
        );

        // ...
    }

    // ...
}
```

### Step 3: Configure

In your config.yml add your desired configuration.

```yml
rate_limit:
    enabled: true
    redis:
        # Your redis server IP (default localhost) and database
        host: 127.0.0.1
        database: 5
    paths:
        # Path to put restriction on
        - path: /api/test
        # Times the path can be accessed
          limit: 1
        # Period in seconds
          period: 5
        # Redis identifier for storing keys (ip or username currently available)
          identifier: ip
``` 

Running tests
============

./vendor/bin/simple-phpunit
