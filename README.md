# Laravel Done Jobs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/omatech/laravel-done-jobs.svg?style=flat-square)](https://packagist.org/packages/omatech/laravel-done-jobs)
[![Total Downloads](https://img.shields.io/packagist/dt/omatech/laravel-done-jobs.svg?style=flat-square)](https://packagist.org/packages/omatech/laravel-done-jobs)

Quick way to save jobs that have been created without any problem. The antithesis of failed jobs.
## Installation

You can install the package via composer:

```bash
composer require omatech/laravel-done-jobs
```

## Usage

Run migrate to create the done_jobs table.

``` php
php artisan migrate
```

Use the command to push a done job into the queue again.

``` php
php artisan queue:rejob {id/all}
```

## Credits

- [Nil Font](https://github.com/omatech)
- [Christian Bohollo](https://github.com/christian-omatech)
- [Adrià Roca](https://github.com/adriaroca)
- [All Contributors](../../contributors)

## Organization

- [Omatech](https://github.com/omatech)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.