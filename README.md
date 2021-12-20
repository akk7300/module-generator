# Quickly Generate Laravel Module

## Installation

You can install the package via composer:

```bash
composer require akk7300/module-generator
```

publish the config files and change namespace:

``` 
php artisan vendor:publish --provider="Akk7300\ModuleGenerator\ModuleGeneratorServiceProvider" --tag="config"
```

## Usage

```
php artisan make:module Blogs
```

### Security

If you discover any security related issues, please email aungkhant2233@gmail.com instead of using the issue tracker.

## Credits

-   [Aung Khant](https://github.com/akk7300)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
