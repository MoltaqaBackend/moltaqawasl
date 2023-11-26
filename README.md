# Wasl: easy way to integrte wasl apis to check for drivers data in saudi aribia

[![Latest Version on Packagist](https://img.shields.io/packagist/v/moltaqa/wasl.svg?style=flat-square)](https://packagist.org/packages/moltaqa/wasl)
[![Total Downloads](https://img.shields.io/packagist/dt/moltaqa/wasl.svg?style=flat-square)](https://packagist.org/packages/moltaqa/wasl)

`moltaqa/wasl` works for Laravel 8 , 9 and 10 applications running on PHP 8.0 and above.

## Official Documentation

The official documentation for moltaqa wasl can be found on the [Moltaqa Packages website](https://pakages.moltaqa.net/docs/wasl).

## Installing WASL

The recommended way to install WASL is through
[Composer](https://getcomposer.org/).

```bash
composer require moltaqa/wasl
```

## Publishing Assets

to publish WASL assets

```bash
php artisan vendor:publish --provider="Moltaqa\Wasl\WaslServiceProvider"
```

## Basic Usage

Use `Wasl::getInstance()` to create and initialize a WASL instance.
```php
# List Supported Vehicle Plate Letters
echo Wasl::getInstance()->getVehiclePlateLetters();

# Register a driver and his vehicle
echo Wasl::getInstance()->registerDriverAndVehicle(array $driverData,array $vehicleData);

# check if a driver identity is registered at WASL
echo Wasl::getInstance()->waslCheckEligibility(mixed $identityNumbers);
```

## Credits

- [Moltaqa](https://moltaqa.net)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
