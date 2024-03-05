# 🔭 PHP Sneaky Peek
[![PHP Version](https://img.shields.io/packagist/php-v/alecrabbit/php-sneaky-peek/dev-main.svg)](https://php.net)
[![Tests Status](https://github.com/alecrabbit/php-sneaky-peek/workflows/tests/badge.svg)](https://github.com/alecrabbit/php-sneaky-peek/actions)

[![Latest Stable Version](https://poser.pugx.org/alecrabbit/php-sneaky-peek/v/stable)](https://packagist.org/packages/alecrabbit/php-sneaky-peek)
[![Packagist Pre Release Version](https://img.shields.io/packagist/vpre/alecrabbit/php-sneaky-peek)](https://packagist.org/packages/alecrabbit/php-sneaky-peek)
[![Latest Unstable Version](https://poser.pugx.org/alecrabbit/php-sneaky-peek/v/unstable)](https://packagist.org/packages/alecrabbit/php-sneaky-peek)

[![License](https://poser.pugx.org/alecrabbit/php-sneaky-peek/license)](https://packagist.org/packages/alecrabbit/php-sneaky-peek)

## A function to get a peek at private parts of an object.

### Installation
```bash
composer require --dev alecrabbit/php-sneaky-peek
```
> This package is intended for DEVELOPMENT purposes only.

### Usage
```php
class Awesome
{
    private $secret = '1234';
    
    protected function protectedMethod() {
        return 'protected';
    }    
    
    protected static function protectedStaticMethod() {
        return 'protected static';
    }    
}

// ...

peek(new Awesome())->secret; // '1234'
peek(new Awesome())->protectedMethod(); // 'protected'
```
In case you want to access static properties or methods you can pass FQCN as an argument instead of an object.
```php
peek(Awesome::class)->protectedStaticMethod(); // 'protected static'
```

### How it is different from [spatie/invade](https://github.com/spatie/invade)?

The main difference is `invade` can work with objects only, while `peek` can work with both objects and FQCN(class-string).
