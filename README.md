# Laravel OTP ▲

## Introduction

This is a simple package for Laravel to generate and validate OTPs (One Time Passwords).

## Installation 💽

Install via composer

```bash
composer require ema-salas/otp-generator
```

Add service provider to the `config/app.php` file

```php
<?php
   /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [
        ...
        EmaSalas\OtpGenerator\OtpServiceProvider::class,
    ];
...
```

Publish

```bash
php artisan vendor:publish
```

Run Migrations

```bash
php artisan migrate
```

## Usage 🧨

>**NOTE**</br>
>To use Otp Generator you must use this Trait into your models

```php
<?php

  use Illuminate\Database\Eloquent\Model;
  use EmaSalas\OtpGenerator\Otp;

  class MyModel extends Model
  {
    use Otp;
  }
```

### Generate OTP

```php
<?php

  $myModel = MyModel::where(['column' => $value])->first();
  $token = $myModel->generateToken(string $identifier, int $digits = 4, int $validity = 10)
```


### Validate OTP

```php
<?php

  $myModel = MyModel::where(['column' => $value])->first();
  $myModel::validateOtp(string $token)
```

* `$token`: The token tied to the identity.


#### Responses

**On Success**

```object
{
  "status": true,
  "message": "OTP is valid"
}
```

**Does not exist**

```object
{
  "status": false,
  "message": "OTP does not exist"
}
```

**Not Valid***

```object
{
  "status": false,
  "message": "OTP is not valid"
}
```

**Expired**

```object
{
  "status": false,
  "message": "OTP Expired"
}
```

## Inspiration
This package was created inspired in next packages
[1]: https://github.com/ichtrojan/laravel-otp/tree/master "ichtrojan/laravel-otp"
[2]: https://github.com/erdemkeren/laravel-otp/tree/master "erdemkeren/laravel-otp"

## Contribution

If you find an issue with this package or you have any suggestion please help out. I am open to PRs.