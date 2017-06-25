[![Latest Stable Version](https://poser.pugx.org/arrilot/dotenv-php/v/stable.svg)](https://packagist.org/packages/arrilot/dotenv-php/)
[![Total Downloads](https://img.shields.io/packagist/dt/arrilot/dotenv-php.svg?style=flat)](https://packagist.org/packages/Arrilot/dotenv-php)
[![Build Status](https://img.shields.io/travis/arrilot/dotenv-php/master.svg?style=flat)](https://travis-ci.org/arrilot/dotenv-php)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/arrilot/dotenv-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/arrilot/dotenv-php/)

# Simple dotenv for PHP

## Introduction

Q: What's the point of this package? How is it any different from those [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv/) and [josegonzalez/php-dotenv](https://github.com/josegonzalez/php-dotenv) well-known packages?

A: Those great packages are NOT for production. They were always meant to be used during local development only.
The main reasons are:
1) Not fast enough
2) Not secure enough

Many people are actually misuse those packages and use them to configure apps in production too.

In contrast this package IS for production.
It uses plain old php array for `.env` content and doesn't touch $_ENV or $_SERVER by default.
As a result it's fast and secure but has less features.

## Installation

1) `composer require arrilot/dotenv-php`

2) Create `.env.php` file to store configuration settings that are environment specific or sensitive.

Example:

```php
<?php

return [
    'DB_USER' => 'root',
    'DB_PASSWORD' => 'secret',
];

```

This file should NEVER be added to version control.

3) Create  `.env.example.php` file and add it to version control. This file should serve as an example for developers how `.env.php` file should look like. 

4) Load `.env.php` file 

```php 
use Arrilot\DotEnv\DotEnv;
DotEnv::load('/path/to/.env.php'); 
```

## Usage

### Getting data

The most used case is to get dotenv variable.

```php
$dbUser = DotEnv::get('DB_USER');
```

You may pass a second parameter, which is gonna be used as default if variable is not set.

```php
$dbUser = DotEnv::get('DB_USER', 'admin');
```

> Note
This is the method you are going to use most of the time.
It makes sense to add a global helper for it to avoid importing the class name and e.t.c.

```php
function env($key, $default = null)
{
    return DotEnv::get($key, $default);
}
...
$dbUser = env('DB_USER', 'admin');
```

You can also get all dotenv variables at once:

```php
$variables = DotEnv::all();
```

### Setting data

You can set or override specific variable like that:

```php
DotEnv::set('DB_USER', 'admin');
DotEnv::set('DB_PASSWORD', 'secret');
// or
DotEnv::set([
    'DB_USER'     => 'root',
    'DB_PASSWORD' => 'secret',
]);
```

You can reload all variables entirely from file or array

```php
DotEnv::load('/path/to/new/.env.php');
//or
DotEnv::load([
    'DB_USER'     => 'root',
    'DB_PASSWORD' => 'secret',
]);
```

### Other methods

There is way to ensure that a specific dotenv variable exists.
Example:

```php
DotEnv::setRequired(['DB_USER', 'DB_PASSWORD']);
```
If the variable is not loaded an `Arrilot\DotEnv\Exceptions\MissingVariableException` will be thrown.

There are also convenient methods to copy all variables to `putenv()`, `$_ENV` or `$_SERVER` if you DO need it, but in most cases you don't

```php
DotEnv::copyVarsToPutenv($prefix = 'PHP_'); // putenv()
DotEnv::copyVarsToEnv(); // $_ENV
DotEnv::copyVarsToServer() // $_SERVER
```

### Testing

Q: Why are there so many static calls? How am I supposed to mock them in tests?

A: You shouldn't mock `DotEnv` class. Just override what you need using `set` or `load` methods.
Note that `load` method understands arrays too.
