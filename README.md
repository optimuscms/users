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

**Parameters**

None

**Example Response**

```json
[
    {
        "id": 24,
        "name": "Jamie Fenwick",
        "email": "jamie@example.com",
        "username": "jamiefenwick",
        "created_at": "2019-02-19 09:14:44",
        "updated_at": "2019-02-19 09:14:50"
    },
    {
        "id": 25,
        "name": "Ellen Patrice",
        "email": "ellen@example.com",
        "username": "ellenpatrice",
        "created_at": "2019-02-19 09:36:23",
        "updated_at": "2019-02-19 09:36:23"
    }
]
```

<a name="users-create"></a>
### Create user
Create a new users who can access the CMS
```http
POST /admin/api/users
```

**Parameters**

| Parameter | Required? | Type  | Description    |
|-----------|-----------|-------|----------------|
| name      |    ✓      | string| The name of the user |
| email     |    ✓      | string| The email address of the user |
| username  |    ✓      | string| A username which will be used to login to the CMS |
| password  |    ✓      | string| A password which will be used to login to the CMS |

**Example Response**

Returns the details of the newly created user. See [single user response example](#example-single-user-response).

<a name="users-get"></a>
### Get user
Get the details of a specific user
```http
GET /admin/api/users/{id}
```

**Parameters**

| Parameter | Required? | Type  | Description    |
|-----------|-----------|-------|----------------|
| id        |    ✓      | int   | The ID of the user to retrieve |

<a name="example-single-user-response"></a>
**Example Response**

```json
{
    "id": 24,
    "name": "Jamie Fenwick",
    "email": "jamie@example.com",
    "username": "jamiefenwick",
    "created_at": "2019-02-19 09:36:23",
    "updated_at": "2019-02-19 09:36:23"
}
```


<a name="users-update"></a>
### Update user
Update the details of a particular user
```http
PATCH /admin/api/users/{id}
```

**Parameters**

| Parameter | Required? | Type  | Description    |
|-----------|-----------|-------|----------------|
| name      |    ✓      | string| The name of the user |
| email     |    ✓      | string| The email address of the user |
| username  |    ✓      | string| A username which will be used to login to the CMS |
| password  |    ✗      | string| A password which will be used to login to the CMS |

**Example Response**

Returns the details of the updated user. See [single user response example](#example-single-user-response).


<a name="users-delete"></a>
### Delete user
Delete a user so they can no longer access the CMS
```http
DELETE /admin/api/users/{id}
```

**Parameters**

| Parameter | Required? | Type  | Description    |
|-----------|-----------|-------|----------------|
| id      |    ✓      | int  | The ID of the user to delete |

**Example Response**

The HTTP status code will be 204 if successful.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
