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
# has an option to calculate the value of "dateOfBirthHijri" 
echo Wasl::getInstance()->registerDriverAndVehicle(
         driverData: [
             "driver" => [
                 "identityNumber" => "1234567890",
                 "dateOfBirthHijri" => "1411/01/01",
                 "dateOfBirthGregorian" => "1990-01-01",
                 "emailAddress" => "address@email.com",
                 "mobileNumber" => "+966512345678",
             ]
         ],
         vehicleData: [
             "vehicle" => [
                 "sequenceNumber" => "123456879",
                 "plateLetterRight" => "ا",
                 "plateLetterMiddle" => "ا",
                 "plateLetterLeft" => "ا",
                 "plateNumber" => "1234",
                 "plateType" => "1"
             ]
         ],
         calcHijriDate: false
     );

# check if a driver registration is still valid at WASL
echo Wasl::getInstance()->driverCheckEligibility(identityNumbers: '1234567890');

# check if list of drivers registrations is still valid at WASL
echo Wasl::getInstance()->driverCheckEligibility(identityNumbers: ['1234567890','1234567891']);
```

## Credits

- [Moltaqa](https://moltaqa.net)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
