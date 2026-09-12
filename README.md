# Generate Saloon Requests for your Api 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/tobya/saloonforge.svg?style=flat-square)](https://packagist.org/packages/tobya/saloonforge)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/tobya/saloonforge/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/tobya/saloonforge/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/tobya/saloonforge.svg?style=flat-square)](https://packagist.org/packages/tobya/saloonforge)

Saloon is really amazing and allows you to generate a set of objects that allow you to call an external api in a controlled fashion.  If you own that api, or have an internal api, Saloon Forge can help you to generate these request classes for all your routes, straight from your routes file.



## Installation

You can install the package via composer:

```bash
composer require tobya/saloonforge
```


You can publish the config file with:

```bash
php artisan vendor:publish --tag="saloonforge-config"
```



## Usage

```bash
php artisan Saloon:Forge Photo
```

This will generate a set of requests and a Connector for the Photo integration you have set up in the config file.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Toby Allen](https://github.com/tobya)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
