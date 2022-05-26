# Quickly Generate Laravel Module

## :inbox_tray: Installation

You can install this package via composer:

```bash
composer require akk7300/module-generator
```

and after installation you can run following command to publish config files:

``` 
php artisan vendor:publish --provider="Akk7300\ModuleGenerator\ModuleGeneratorServiceProvider" --tag="config"
```
## :gear: Configuration
for configure this package go to `config/modulegenerator.php` and if you want to customize namespace you can do like this

```php
<?php  
  
return [
    'namespace' => 'Laravel',
];
```

add line in `composer.json` `autoload` block

```
"autoload": {
        "psr-4": {
            // 
            "Laravel\\": "modules/"
        }
    },
```
and then 

```
composer dump-autoload
```
## Usage

```
php artisan make:module Blog
```

### Security

If you discover any security related issues, please email aungkhant2233@gmail.com instead of using the issue tracker.

## Credits

-   [Aung Khant](https://github.com/akk7300)
-   [Htet Oo Zin](https://github.com/htetoozin)
-   [All Contributors](../../contributors)

## :scroll: License 

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
