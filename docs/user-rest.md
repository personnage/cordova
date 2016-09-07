# Users

## List users

Get a list of users.

This function takes pagination parameters `page` and `per_page` to restrict the list of users.

    GET /users

### For normal users

    [
      {
        "id": 2,
        "name": "Prof. Frederic Veum",
        "username": "mariano65"
      },
      {
        "id": 3,
        "name": "Mrs. Alexandrea Deckow DDS",
        "username": "lorine.monahan"
      },
      {
        "id": 4,
        "name": "Jett O'Conner III",
        "username": "bruen.derek"
      }
    ]

### For admins

    [
      {
        "id": 2,
        "name": "Prof. Frederic Veum",
        "email": "oconner.roselyn@example.com",
        "username": "mariano65",
        "is_admin": false,
        "created_at": "Mon, Aug 29, 2016 7:10 AM"
      },
      {
        "id": 3,
        "name": "Mrs. Alexandrea Deckow DDS",
        "email": "xrice@example.net",
        "username": "lorine.monahan",
        "is_admin": true,
        "created_at": "Mon, Aug 29, 2016 7:10 AM"
      },
      {
        "id": 4,
        "name": "Jett O'Conner III",
        "email": "dion61@example.net",
        "username": "bruen.derek",
        "is_admin": false,
        "created_at": "Mon, Aug 29, 2016 7:10 AM"
      }
    ]

You can search for users by email or username with: /users?search=John

## Single user

Get a single user.

### For user

    GET /users/:id

Parameters:
- id (required) - The ID of a user

    {
      "id": 1,
      "name": "Mr. Judah Gislason PhD",
      "username": "bhuel",
    }

### For admin

    {
      "id": 1,
      "name": "Mr. Judah Gislason PhD",
      "email": "donato58@example.com",
      "username": "bhuel",
      "is_admin": true,
      "created_at": "Mon, Aug 29, 2016 7:10 AM"
    }


## User creation

Creates a new user. Note only administrators can create new users.

    POST /users

Parameters:
- email (required) - Email
- password (required) - Password
- username (required) - Username
- name (required) - Name
- admin (optional) - User is admin - true or false (default)

## User modification

Modifies an existing user. Only administrators can change attributes of a user.

    PUT /users/:id 

Parameters:
- email - Email
- password - Password
- username - Username
- name - Name
- admin - User is admin - true or false (default)

Note, at the moment this method does only return a 404 error, even in cases where a 422 (Unprocessable Entity) would be more appropriate, e.g. when renaming the email address to some existing one.

## User deletion

Deletes a user. Available only for administrators. This is an idempotent function, calling this function for a non-existent user id still returns a status code 200 OK. The JSON response differs if the user was actually deleted or not. In the former the user is returned and in the latter not.

    DELETE /users/:id

Parameters:
- id (required) - The ID of the user

## User deletion (soft delete)

Mark up a user as deleted. Available only for administrators.

    PATCH /users/:id/delete

Parameters:
- id (required) - The ID of the user

## User restore

Reset a user after soft delete. Available only for administrators.

    PATCH /users/:id/restore

Parameters:
- id (required) - The ID of the user

## Current user

Gets currently authenticated user.

    GET /user

    {
      "id": 1,
      "name": "Mr. Judah Gislason PhD",
      "email": "donato58@example.com",
      "username": "bhuel",
      "is_admin": true,
      "created_at": "Mon, Aug 29, 2016 7:10 AM"
    }

## Modifies currently authenticated user.

    PUT /user

    Parameters:
    - email - Email
    - password - Password
    - username - Username
    - name - Name

## Block user

Blocks the specified user. Available only for admin.

    PATCH /users/:id/block

Parameters:
- id (required) - id of specified user

Will return 200 OK on success, 404 User Not Found is user cannot be found or 403 Forbidden when trying to block an already blocked user.

## Unblock user

Unblocks the specified user. Available only for admin.

    PATCH /users/:id/unblock

Parameters:
- id (required) - id of specified user

Will return 200 OK on success, 404 User Not Found is user cannot be found or 403 Forbidden when trying to block an already blocked user.
