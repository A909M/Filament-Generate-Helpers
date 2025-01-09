[![Stand With Palestine](https://raw.githubusercontent.com/TheBSD/StandWithPalestine/main/banner-no-action.svg)](https://TheBSD.github.io/StandWithPalestine/)

# Filament-Generate-Helpers

[![StandWithPalestine](https://raw.githubusercontent.com/TheBSD/StandWithPalestine/main/badges/StandWithPalestine.svg)](https://github.com/TheBSD/StandWithPalestine/blob/main/docs/README.md)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/a909m/filament-generate-helpers.svg?style=flat-square)](https://packagist.org/packages/a909m/filament-generate-helpers)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/a909m/filament-generate-helpers/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/a909m/filament-generate-helpers/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/a909m/filament-generate-helpers/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/a909m/filament-generate-helpers/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/a909m/filament-generate-helpers.svg?style=flat-square)](https://packagist.org/packages/a909m/filament-generate-helpers)

Effortlessly create reusable form fields and table column helpers for Filament resources based on Laravel models. This package is not just about generating code—it introduces a methodology for managing forms and tables in your Filament projects, making them reusable and easier to maintain.

---

## Why Use This Package?

When building a Filament project, it's common to encounter repetitive definitions for **forms** and **tables** in various contexts, such as:

-   **Resources**: A resource's form and table are defined independently.
-   **Relation Managers**: You might reuse a form or table inside a relation manager.
-   **Table widgets or Custom Pages**: Forms and tables are often replicated here.
-   **Select Forms (`createOptionForm`)**: Similar forms might be required when creating options in a `Select` field.

### The Problem

If a change is required in the form or table (e.g., adding a new field or column), you have to update it in multiple places. This is time-consuming, error-prone, and violates the **DRY (Don't Repeat Yourself)** principle.

### The Solution

This package provides a systematic approach:

-   Centralizes the definitions of **form fields** and **table columns** in reusable traits and helper classes.
-   Allows you to make changes in **one place** and have them reflected everywhere.

---

## Features

-   **Reusable Form Fields**: Generate reusable form fields organized into a trait.
-   **Reusable Table Columns**: Generate reusable table columns organized into a trait.
-   **Centralized Customization**: Changes to the traits automatically propagate across all resources, relation managers, Table widgets, etc.
-   **Seamless Integration**: Works effortlessly with Filament's workflow.

---

## Installation

You can install the package via composer:

```bash
composer require a909m/filament-generate-helpers
```

## Usage

### Step 1: Generate Helpers

To generate helpers for a model, use the following Artisan command:

```php
php artisan filament-generate-helpers:run User
```

Under the hood, this command leverages Filament's built-in feature for automatically generating forms and tables. You can learn more about this feature in the [Filament documentation](https://filamentphp.com/docs/3.x/panels/resources/getting-started#automatically-generating-forms-and-tables).

This will create the following structure in your `app/Filament/Helpers` directory:

```
.
+-- Helpers
|   +-- User
|   |   +-- UserHelper.php
|   |   +-- Traits
|   |   |   +-- UserFormFields.php
|   |   |   +-- UserTableColumns.php
```

### Step 2: Use the Helpers

In your Filament resources, relation managers, Table widgets, or any other context, you can reuse the generated helpers.

**Example:** Using Helpers in a Resource

```php
namespace App\Filament\Resources\UserResource;

use App\Filament\Helpers\User\UserHelper;
use Filament\Resources\Resource;

class UserResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form->schema(
            UserHelper::formFields()
        );
    }

    public static function table(Table $table): Table
    {
        return $table->columns(
            UserHelper::tableColumns()
        );
    }
}
```

## How It Works

The package organizes the generated code into a helper class and two traits:

1. **Helper Class:** Combines and exposes reusable methods for form fields and table columns.
2. **Traits:** Separately define reusable form fields and table columns.

### Generated Helper Example

**For the `User` model:**

```php
<?php

namespace App\Filament\Helpers\User;

use App\Filament\Helpers\User\Traits\UserFormFields;
use App\Filament\Helpers\User\Traits\UserTableColumns;

class UserHelper
{
    use UserFormFields, UserTableColumns;

    public static function formFields(): array
    {
        return [
            static::nameField(),
            static::emailField(),
            static::passwordField(),
        ];
    }

    public static function tableColumns(): array
    {
        return [
            static::nameColumn(),
            static::emailColumn(),
            static::createdAtColumn(),
        ];
    }
}
```

# Benefits

**1. Single Source of Truth**
Define your form fields and table columns once. Use them across:

-   Resources

*   Relation Managers

-   Table widgets
-   Select Forms (`createOptionForm`)

**2. Ease of Maintenance**

-   Make a change in the generated traits, and it’s reflected everywhere the helper is used.

**3. Consistency**

-   Ensure consistent form and table structures throughout your project.

## Practical Example: Reusing a Form Across Contexts

Imagine you have a form in your `UserResource`, and you want to reuse it in:

1. A relation manager (e.g., assigning roles to users).
2. A widget (e.g., creating a quick form for new users).

Instead of copying the same schema to multiple places:

```php
return $form->schema([
    TextInput::make('name')->required(),
    TextInput::make('email')->required(),
    Password::make('password')->required(),
]);
```

You can simply call:

```php
return $form->schema(UserHelper::formFields());
```

Now, if you need to update the form (e.g., add a `date_of_birth` field), you update it in one place—the `UserFormFields` trait.

## Conclusion

This package is not just a tool—it’s a best practice for managing reusable forms and tables in Filament projects. By centralizing your form and table logic, you save time, reduce redundancy, and ensure consistency.

Install it today and streamline your development workflow! 🎉

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

-   [Assem Alwaseai](https://github.com/A909M)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
