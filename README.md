# Laravel Postal Code Validation
Worldwide postal code validation for Laravel, based on Google's Address Data Service.

<p align="center">
    <a href="https://github.com/axlon/laravel-postal-code-validation/actions">
        <img src="https://github.com/axlon/laravel-postal-code-validation/workflows/tests/badge.svg" alt="Build status">
    </a>
    <a href="https://packagist.org/packages/axlon/laravel-postal-code-validation">
        <img src="https://img.shields.io/packagist/dt/axlon/laravel-postal-code-validation" alt="Downloads">
    </a>
    <a href="https://github.com/axlon/laravel-postal-code-validation/releases">
        <img src="https://img.shields.io/packagist/v/axlon/laravel-postal-code-validation" alt="Latest version">
    </a>
    <a href="LICENSE.md">
        <img src="https://img.shields.io/packagist/l/axlon/laravel-postal-code-validation" alt="License">
    </a>
</p>

- [Requirements](#requirements)
- [Installation](#installation)
    - [Lumen](#lumen)
- [Usage](#usage)
    - [Available rules](#available-rules)
    - [Customizing the error message](#customizing-the-error-message)
    - [Manually validating](#manually-validating)
    - [Country formats](#country-formats)
    - [Overriding rules](#overriding-rules)
- [Changelog](#changelog)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Requirements
This package has the following requirements:

- PHP 7.2 or higher
- Laravel (or Lumen) 6.0 or higher

## Installation
You can install this package with Composer, by running the command below:

```bash
composer require axlon/laravel-postal-code-validation
```

If you have package discovery enabled, that's it, continue to the [usage](#usage) section. If you want to register the
package manually, you can do this by adding the following line to your `config/app.php` file:

```php
'providers' => [
   ...
   Axlon\PostalCodeValidation\ValidationServiceProvider::class,
   ...
],
```

### Lumen
If you are using Lumen, register the package by adding the following line to your `bootstrap/app.php` file:

```php
$app->register(Axlon\PostalCodeValidation\ValidationServiceProvider::class);
```

## Usage
Postal code validation perfectly integrates into your Laravel application, you can use it just like you would any
framework validation rule.

### Available rules
This package adds the following validation rules:

#### postal_code:foo,bar,...
The field under validation must be a valid postal code in at least one of the given countries. Arguments must be
countries in [ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) format.

```php
'postal_code' => 'postal_code:NL,BE',
// or...
'postal_code' => PostalCode::for('NL', 'BE'),
```

#### postal_code_with:foo,bar,...
The field under validation must be a postal code in at least one of the countries in the given fields.

```php
'country' => '...',
'postal_code' => 'postal_code_with:country',
// or...
'postal_code' => PostalCode::with('country'),
```

### Customizing the error message
To override the default error message, you may add the following lines to your
`resources/lang/{language}/validation.php` file:

```php
'postal_code' => 'Your message here',
'postal_code_with' => 'Your message here',
```

The following placeholders will be automatically filled for you:

Placeholder | Description
------------|------------
:attribute  | The name of the field that was under validation
:countries  | The countries that were validated against (e.g. `NL, BE`)*
:examples   | Examples of allowed postal codes (e.g. `1234 AB, 4000`)*

*The `:countries` and `:examples` placeholders may be empty if no valid countries are passed.

### Manually validating
If you want to validate postal codes manually outside of Laravel's validation system, you may call the validator
directly, like so:

```php
PostalCode::validate($postalCode, $countries); // returns a boolean
```

### Country formats

> This feature requires [league/iso3166](https://packagist.org/packages/league/iso3166)

This package uses [ISO 3166-1 alpha-2](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2) country codes as
parameters by default, however it also supports [ISO 3166-1 alpha-3](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-3)
and [ISO 3166-1 numeric](https://en.wikipedia.org/wiki/ISO_3166-1_numeric) formats.

To validate using ISO 3166-1 alpha-3 country codes:

```php
PostalCode::useAlpha3();
// ...
PostalCode::validate($postalCode, 'USA');
```

Likewise, to use ISO 3166-1 numeric country codes:

```php
PostalCode::useNumeric();
// ...
PostalCode::validate($postalCode, 840);
```

Note that this works for both the validation rules and manual validation, if you are using this for validation rules it
is recommended to set this in a service provider.

### Overriding rules
Depending on your use case you may want to override the patterns used to validate postal codes for a country. You can do
this by adding the code below in a central place in your application (e.g. a service provider):

```php
PostalCode::override('country', '/your pattern/');

// You can also pass overrides as an array

PostalCode::override([
    'country 1' => '/pattern 1/',
    'country 2' => '/pattern 2/',
]);
```

**Important**: If you believe there is a bug in one of the patterns that this package ships with, please create an
[issue](https://github.com/axlon/laravel-postal-code-validation/issues/new) in the issue tracker.

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits
- [Choraimy Kroonstuiver](https://github.com/axlon)
- [All contributors](https://github.com/axlon/laravel-postal-code-validation/contributors)

## License
This open-source software is licenced under the [MIT license](LICENSE.md). This software contains code generated from
Google's Address Data Service, more information on this service can be found
[here](https://github.com/google/libaddressinput/wiki/AddressValidationMetadata).
