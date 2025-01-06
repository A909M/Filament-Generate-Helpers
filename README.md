# Filament-Generate-Helpers
[![Latest Version on Packagist](https://img.shields.io/packagist/v/a909m/filament-generate-helpers.svg?style=flat-square)](https://packagist.org/packages/a909m/filament-generate-helpers)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/a909m/filament-generate-helpers/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/a909m/filament-generate-helpers/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/a909m/filament-generate-helpers/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/a909m/filament-generate-helpers/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/a909m/filament-generate-helpers.svg?style=flat-square)](https://packagist.org/packages/a909m/filament-generate-helpers)

Effortlessly generate reusable form fields and table column helpers for Filament resources based on Laravel models, streamlining your development workflow.


## Features

- Automatically generates reusable **form fields** and **table columns**.
- Organizes fields and columns into traits and a helper class.
- Simplifies resource management for Filament developers.


## Installation

You can install the package via composer:

```bash
composer require a909m/filament-generate-helpers
```


## Usage

To Generate a Helpers for the `App\Models\User`model, run:
```php
php artisan filament-generate-helpers:run User
```
This will create the following files under the `app/Filament/Helpers/User` directory:

```
.
+-- Helpers
|   +-- User
|   |   +-- UserHelper.php
|   |   +-- Traits
|   |   |   +-- UserFormFields.php
|   |   |   +-- UserTableColumns.php
```
# Under the Hood 
The generated helpers are similar to Filament's [Automatically generating forms and tables](https://filamentphp.com/docs/3.x/panels/resources/getting-started#automatically-generating-forms-and-tables) 
 but are grouped into traits for better reusability. For example, for the `User` model:

```
<?php
namespace App\Filament\Helpers\User;
use App\Filament\Helpers\User\Traits\UserFormFields;
use App\Filament\Helpers\User\Traits\UserTableColumns;

class UserHelper
{
    use  UserFormFields ,UserTableColumns;


    public static function formFields(): array
    {
        return [
            static::nameField(),
            static::emailField(),
            static::emailVerifiedAtField(),
            static::passwordField(),

        ];
    }

    public static function tableColumns(): array
    {
        return [
            static::nameColumn(),
            static::emailColumn(),
            static::emailVerifiedAtColumn(),
            static::createdAtColumn(),
            static::updatedAtColumn(),

        ];
    }
}
```
# Benefits
- Separation of Concerns: Fields and columns are neatly organized into traits.
- Reusability: The helper and traits can be reused across multiple resources.
- Customization: Easily modify the generated traits and helper classes.
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

- [Assem Alwaseai](https://github.com/A909M)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
