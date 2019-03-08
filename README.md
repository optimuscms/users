# Optimus Users

This package provides the core backend functionality for managing the users who can access the CMS.

## Installation

This package can be installed through Composer.

```bash
composer require optimuscms/users
```

In Laravel 5.5 and above the package will autoregister the service provider. 

In Laravel 5.4 you must install this service provider:
```php
// config/app.php
'providers' => [
    ...
    Optimus\Users\UserServiceProvider::class,
    ...
];
```


## Usage

### Api routes

```http
GET /admin/api/users
```

```http
POST /admin/api/users
```

```http
GET /admin/api/users/{id}
```

```http
GET /admin/api/user
```

```http
PATCH /admin/api/users/{id}
```

```http
DELETE /admin/api/users/{id}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
