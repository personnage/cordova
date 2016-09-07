# Registration and authorization

## Register

**POST** `/api/register`

**Request Headers**:
    
- *Accept*:
    - application/json

**Parameters**: 

- email    - Required
- password - Required Min 6 letter

**Response Header**:

- *Content-Type*:
    - application/json

**Response JSON Object**:

- ok (boolean) – Operation status

**Status Codes**:

- 201 Created - User was created
- 422 Unprocessable Entity - User was not created. Validate error.


**Request**:

    POST /api/register HTTP/1.1
    Accept: application/json

    {
        "email": "mail@example.com",
        "password": "my-password",
    }

**Response**:

    HTTP/1.1 201 Created
    Cache-Control: no-cache
    Content-Type: application/json
    Date: Fri, 26 Aug 2016 19:00:34 GMT
    
    {
        "ok": true
    }

**Request**:

    POST /api/register HTTP/1.1
    Accept: application/json
    
    {
        "email": "mail@example",
        "password": "1234",
    }

**Response**:

    HTTP/1.1 422 Unprocessable Entity
    Cache-Control: no-cache
    Content-Type: application/json
    Date: Fri, 26 Aug 2016 19:00:34 GMT
    
    {
      "email": [
        "The email must be a valid email address."
      ],
      "password": [
        "The password must be at least 6 characters."
      ]
    }

## Reset password

**POST** `/api/password/reset`

**Request Headers**:
    
* *Accept*:
    * application/json

**Parameters**: 

* email - Required

**Response Header**:

* *Content-Type*:
    * application/json

**Response JSON Object**:

* ok (boolean) – Operation status
* email (array) - Message if email not valid or not found

**Status Codes**:

* 202 Accepted - 
* 404 Not Found - 
* 422 Unprocessable Entity -

**Request**:

    POST /api/password/reset HTTP/1.1
    Accept: application/json
    
    {
        "email": "mail@example.com"
    }

**Response**:

    HTTP/1.1 202 Accepted
    Cache-Control: no-cache
    Content-Type: application/json
    Date: Sat, 27 Aug 2016 05:54:49 GMT
    
    {
        "ok": true
    }

**Request**:

    POST /api/password/reset HTTP/1.1
    Accept: application/json
    
    {
        "email": "supermail@example.org"
    }

**Response**:

    HTTP/1.1 404 Not Found
    Cache-Control: no-cache
    Content-Type: application/json
    Date: Fri, 26 Aug 2016 19:00:34 GMT
    
    {
      "ok": false,
      "email": [
        "We can't find a user with that e-mail address."
      ]
    }


## Authentication

**POST** `/oauth/token`

**Request Headers**:
    
- *Accept*:
    - application/json

**Parameters**: 

- grant_type    - *password*
- client_id     -
- client_secret -
- username      - email address or username
- password      -

**Response Header**:

- *Content-Type*:
    - application/json

**Response JSON Object**:

- token_type (string)    - Bearer
- expires_in (int)       - Token lifetime
- access_token (string)  - Token to link with api
- refresh_token (string) - Token to update access token

---

- error   - short error message
- message - full error message

**Status Codes**:

- 200 Ok - 
- 400 Bad Request - 
- 401 Unautorized - 
- 422 Unprocessable Entity -    

**Request**:

    POST /oauth/token HTTP/1.1
    Accept: application/json
    
    {
        "grunt_type": "password",
        "client_id": 2,
        "client_secret": "YyPDodE656rF4X80bv8VxHXvwjR2G6KGiq3BeGlG",
        "username": "mail@example.com",
        "password": "my-password"
    }

**Response**:

    HTTP/1.1 200 Ok
    Cache-Control: no-cache
    Content-Type: application/json
    Date: Sat, 27 Aug 2016 05:54:49 GMT
    
    {
      "token_type": "Bearer",
      "expires_in": 600,
      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImNmYzc3MGQwYThmMTgyMWVlOTdjNzc1OTcxNWExOTNlNDc4YjAwM2U0YjgwMzVlOWQzNWY1OGUwN2I4MTA5NjRkODQ2NTllZmEzMGEyMThhIn0.eyJhdWQiOiIyIiwianRpIjoiY2ZjNzcwZDBhOGYxODIxZWU5N2M3NzU5NzE1YTE5M2U0NzhiMDAzZTRiODAzNWU5ZDM1ZjU4ZTA3YjgxMDk2NGQ4NDY1OWVmYTMwYTIxOGEiLCJpYXQiOjE0NzIyNzkyMzEsIm5iZiI6MTQ3MjI3OTIzMSwiZXhwIjoxNDcyMjc5ODMxLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.gKrTWj1zCU4key1LnrZJuXFdoUCISAe4UhFov07FS0fkpQ049BN5c1xv6n6l74eigP_dDPp7tM_7fr3Y8cWHtYo3iugY6UBkCnltNkOUyUwLhIsaiJDqbdAu61xOC_6MkKv4DjrMPV44MBTRgS-Qwd4fAJppWUutb-2LLKbvqKis-j7d6ckvgNUhSX85UGUpdz01_YfzxyDFpMlSFwPNnKREsu8vztgo_bK__kOxP6YMUohM4Lrx0By3mocMJnuzmKhIFFjtheiT6ozYGTy0jHRXCpeWJtO5FYeku9X_-fF63Wl3lRu1JsYh_XQcUJ89lbDRzZUe2nC-hMLB56-byMJsQv5t2Ti0PsSGTWzoVqIEMKj1nbNlJKEM5f9lnnIcCncOH5Pt6wY-Nkn5KSzFTUtBTusk6DATp1A_4Cs2SP4ynfvq8yc8KJI-nLxokZ7ixnqss2IU9DxP8uQH2etuSRtbKxQqUNUkjmMcNbfcamnXI4y11l77ULI5vn7smZkQY3EEMHhbZMo5j8ucQujM7e8kpGx-k_f8kp3k3erhl0Yfxsgu6SLCO6QSpdTTUWXu3tfdls8z5nMwrDbEWmTLxgqogUiyi4jBg-HRdwHlrMnqyZ6OzYT0sJ5L0NBBtLcJbuskLOp7Vvduw3YDeOPTg3Xb3fECEqova3exFAMey9s",
      "refresh_token": "mBKvumwia27MyMVEcvPGDjkQLEvnHMkhAvhY1UOTLViIaCCQAm5ljgEPr/H9184K2dArOEX9KBIacLKFpXCG8mIwWRUnjq9VvC/lcg5Tp4v+u/vN6A1xwCU+Zvd+lmc9O23vcOcrva8IVaOA50KIErHE238Yr31XfrpEnREJbR0g4de/kkwwyGG6H507uGgjboRoOmE93SuiYv+lMKEdDZQiSuiAGJgaehWuMZGpPRUeyBPO4dZbJxIk36dRc5SV98/g1Ym/kVJlfonXONzExVYYtlE9i6aUho8Eqnq96hyz665jbZDxMVERmTOMva5goWOvcEjLTdQT73mp9FqVgOZtYlkLsaxD2qYoVTz3csBnBL3XJdmUE33AHpMojAUZqPM+lbxhLakJKPX7acVeisUgq2xtlm1cmorTaeR69M9RSZ4+8f2jepaKhJp30MlkJp8O3qFEfRyHk3Mu02+CwhCt5jM3ZRsLRo5Cmy0Bv+iRIQOHIS9CdDnDS6VxoLhrCiIVPyvjxLrLdRZh61pDmKjhe0ZTPlUr35/NrF/YX6ObvxeSo9yLZslnbd4twVLOUaU/2yEaYgMAw7ycu/SxKhgRHxsGfIdkcZO/8bTKU1f+u1MzaF6W8jgczf87B8FCyQEPssdNkOYCHsUaskBdap6ff3zyOSOmuiZnmaWtzms="
    }

**Request**:

    POST /oauth/token HTTP/1.1
    Accept: application/json
    
    {
        "grunt_type": "password",
        "client_id": 2,
        "client_secret": "YyPDodE656rF4X80bv8VxHXvwjR2G6KGiq3BeGlG",
        "username": "mail@example.com",
        "password": "bad-password"
    }

**Response**:

    HTTP/1.1 401 Unautorized
    Cache-Control: no-cache
    Content-Type: application/json
    Date: Fri, 26 Aug 2016 19:00:34 GMT
    
    {
      "error": "invalid_credentials",
      "message": "The user credentials were incorrect."
    }


**Request**:

    POST /oauth/token HTTP/1.1
    Accept: application/json
    
    {
        "grunt_type": "password"
    }

**Response**:

    HTTP/1.1 400 Bad Request
    Cache-Control: no-cache
    Content-Type: application/json
    Date: Fri, 26 Aug 2016 19:00:34 GMT
    
    {
      "error": "invalid_request",
      "message": "The request is missing a required parameter, includes an invalid parameter value, includes a parameter more than once, or is otherwise malformed.",
      "hint": "Check the `client_id` parameter"
    }




