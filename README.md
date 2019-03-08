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


## API Routes

The API follows standard RESTful conventions, with responses being returned in JSON. 
Appropriate HTTP status codes are provided, and these should be used to check the outcome of an operation.

**Users**
 - [List users](#users-all)
 - [Get user](#users-get)
 - [Create user](#users-create)
 - [Update user](#users-update)
 - [Delete user](#users-delete)

<a name="users-all"></a>
### List users
List all registered users
```http
GET /admin/api/users
```

<a name="users-create"></a>
### Create user
Create a new users who can access the CMS
```http
POST /admin/api/users
```

<a name="users-get"></a>
### Get user
Get the details of a specific user
```http
GET /admin/api/users/{id}
```

<a name="users-update"></a>
### Update user
Update the details of a particular user
```http
PATCH /admin/api/users/{id}
```

<a name="users-delete"></a>
### Delete user
Delete a user so they can no longer access the CMS
```http
DELETE /admin/api/users/{id}
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
