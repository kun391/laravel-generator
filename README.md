# Laravel Generator

[![Latest Stable Version](https://poser.pugx.org/kun391/laravel-categories/v/stable)](https://packagist.org/packages/kun391/laravel-categories) [![Total Downloads](https://poser.pugx.org/kun391/laravel-categories/downloads)](https://packagist.org/packages/kun391/laravel-categories) [![Latest Unstable Version](https://poser.pugx.org/kun391/laravel-categories/v/unstable)](https://packagist.org/packages/kun391/laravel-categories) [![License](https://poser.pugx.org/kun391/laravel-categories/license)](https://packagist.org/packages/kun391/laravel-categories)

The package use to generate a module with JSONAPI spec.

## Install

Via Composer

``` bash
$ composer require kun391/laravel-generator
```

## Setup

Add the following to your composer.json file :
```
"require": {
    "kun391/laravel-generator": "dev-master",
},
```

Then register service provider with in config/app.php:
```
'providers' => [
    ...
    Kun\Categories\GeneratorServiceProvider::class,
]
```

and add alias Input to aliases

'aliases' => [
    ...
    'Input' => Illuminate\Support\Facades\Input::class,
]

## Usage

Run this command with format:

```
    php artisan generate:run events --dir='./packages' --namespace='Kun\Events'
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
