# PHP-BIN-Lookup

PHP-BIN-Lookup is a versatile package designed for looking up bank information using BIN (Bank Identification Number). It can be used as a standalone package or within a Laravel project.

## Installation

### With Composer

1. Install the package via Composer:

    ```shell
    composer require wessama/php-bin-lookup
    ```

### Manual Installation

1. Clone the repository into your project:

    ```shell
    git clone https://github.com/wessama/php-bin-lookup.git
    ```

2. Navigate to the package directory and install the dependencies:

    ```shell
    cd php-bin-lookup
    composer install
    ```

## Configuration

### Environment Variables

PHP-BIN-Lookup can utilize environment variables from a `.env` file. If used within a Laravel project, it will automatically use the Laravel project’s `.env` file. For non-Laravel projects, you need to load the `.env` file manually if it's located in a different directory.

To load a custom `.env` file, use the `loadEnv` method:

```php
PHPBINLookup::loadEnv('/path/to/your/env/directory');
```


## Laravel Service Provider and Configuration Publishing
If you are using this package within a Laravel project, register the service provider in your config/app.php:

```php
'providers' => [
    // ...
    Wessama\PHPBINLookup\PHPBINLookupServiceProvider::class,
],
```

Then, publish the package’s configuration file to your application’s config directory:

```
php artisan vendor:publish --provider="Wessama\PHPBINLookup\PHPBINLookupServiceProvider"
```

### Usage
Provide usage examples and documentation here.

### Support
For support, please open an issue on the GitHub repository.

### License
PHP-BIN-Lookup is open-sourced software licensed under the MIT license.
