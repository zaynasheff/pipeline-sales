# This is my package pipeline-sales

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zaynasheff/pipeline-sales.svg?style=flat-square)](https://packagist.org/packages/zaynasheff/pipeline-sales)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/zaynasheff/pipeline-sales/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/zaynasheff/pipeline-sales/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/zaynasheff/pipeline-sales/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/zaynasheff/pipeline-sales/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/zaynasheff/pipeline-sales.svg?style=flat-square)](https://packagist.org/packages/zaynasheff/pipeline-sales)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require zaynasheff/pipeline-sales
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="pipeline-sales-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="pipeline-sales-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="pipeline-sales-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$pipelineSales = new Zaynasheff\PipelineSales();
echo $pipelineSales->echoPhrase('Hello, Zaynasheff!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Roman Zaynashev](https://github.com/zaynasheff)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
