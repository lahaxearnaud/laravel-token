Laravel token
=============

[![Build Status](https://travis-ci.org/lahaxearnaud/laravel-token.svg?branch=develop)](https://travis-ci.org/lahaxearnaud/laravel-token)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/2f9abd1c-42a6-4a80-88f4-e1687b1d361a/mini.png)](https://insight.sensiolabs.com/projects/2f9abd1c-42a6-4a80-88f4-e1687b1d361a)
[![CodeClimat](https://d3s6mut3hikguw.cloudfront.net/github/lahaxearnaud/laravel-token/badges/gpa.svg)](https://codeclimate.com/github/lahaxearnaud/laravel-token)
[![Test Coverage](https://codeclimate.com/github/lahaxearnaud/laravel-token/badges/coverage.svg)](https://codeclimate.com/github/lahaxearnaud/laravel-token)
[![License](https://poser.pugx.org/leaphly/cart-bundle/license.svg)](https://github.com/lahaxearnaud/cook-bookmarks)


## Table of Contents

+ [Installation](#installation)
    + [Database](#database)
    + [Provider](#provider)
    + [Facade](#facade)
+ [Usage](#usage)
    + [Create token](#create-token)
    + [Crypt token](#crypt-token)
    + [Validate token](#validate-token)
    + [Route filter](#route-filter)
    + [Events](#events)
+ [API](#api)
    + [Security](#security)
    + [Creation](#creation)
    + [Deletion](#deletion)
    + [Validation](#validation)
    + [Find](#find)

## Installation


```
{
    "require": {
        "lahaxearnaud/laravel-token": "~0.1"
    }
}
```

### Database

```
$ php artisan migrate --package="lahaxearnaud/laravel-token"
```

### Provider

```
	'providers' => array(
        // ...
		'Lahaxearnaud\LaravelToken\LaravelTokenServiceProvider',
	),
```

### Facade

```
	'aliases' => array(
        // ...
		'Token' => 'Lahaxearnaud\LaravelToken\LaravelTokenFacade',
	),
```

## Usage

### Create token

```
    $token = Token::create($userID);
```

### Crypt token

```
    $token = Token::create($userID);
    $cryptToken = Token::cryptToken($token->token);
```

### Validate token

If you crypt your token

```
    $tokenStr = Input::get('token');

    $cryptToken = Token::isValidCryptToken($token->token, $userId);
```

If you don't crypt your token:

```
    $tokenStr = Input::get('token');

    $cryptToken = Token::isValidToken($token->token, $userId);
```

If you use those functions the token is not burn. It can be use many times.

For one shot usage token:

```
    $tokenStr = Input::get('token');

    /**
      * if the token is crypt do :
      * $tokenStr = Token::uncryptToken($tokenStr);
    **/

    $tokenValid = true;
    try {
        // find the token
        $token = $token->findByToken($tokenStr, $userId);

        // test the token validity
        if (Token::isValidToken($token)) {

            // do what you need to do

            // delete the token
            Token::burn($token);
        } else {
            $tokenValid = false;
        }
    } catch (ModelNotFoundException $e) {
        $tokenValid = false;
    }

    if($tokenValid) {
        // manage errors
    }
```

### Route filter

Simple token protection:

```
    Route::get('/token-protected', array('before' => 'auth.token', function () {
        echo "I am token protected";
    }));
```

Authentification by token

```
    Route::get('/login-by-token', array('before' => 'auth.token.auth', function () {
        echo Auth::user()->username;
    }));
```

If an error occur on token validation a http error (``401``) is send to the browser. 


By default you can send your token in parameter, cookie or header. The default name of the field is ``token`` but you 
can change it by publishing and change the configuration:

```
    $ php artisan config:publish lahaxearnaud/laravel-token
```

Then change the tokenFieldName ``config/packages/lahaxearnaud/laravel-token/config.php``.

### Events

You can listen events:

- Token not found
    - name: ``token.notFound`` 
    - parameters:
        - the token string
- Token not valid
    - name: ``token.notValid``
    - parameters:
        - the token object
- Token doesn't allow to be used for login
    - name: ``token.notLoginToken``
    - parameters:
        - the token object
- The user can't logged with a token
    - name: ``token.notLoggableUser``
    - parameters:
        - the token object
        - the user object
- Token burn
    - name: ``token.burned``
    - parameters:
        - the token object
- Token created
    - name: ``token.created``
    - parameters:
        - the token object
- Token saved
    - name: ``token.saved``
    - parameters:
        - the token object

## API

### Security

Crypt a string token

```
    Token::cryptToken ($uncrypt)
```

Uncrypt a string token

```
    Token::uncryptToken ($crypt)
```

### Creation

Create a Token instance (directly saved in database)

```
    Token::create ($userId, $lifetime = 3600, $length = 100)
```


### Deletion

Delete the token

```
    Token::burn (Token $token)
```

### Validation

Fetch the token, check id the token has the good user ID and if it is not expired

```
    Token::isValidToken ($token, $userId)
```

Same as isValidToken but uncrypt the token before trying to find him

```
    Token::isValidCryptToken ($token, $userId)
```

Only validate if the token is expired

```
    Token::isValid (Token $token)
```

### Find

Find the token by ID

```
    Token::find ($id)
```

Find the token by token string

```
    Token::findByToken ($token, $userId)
```

Find all token for an user

```
    Token::findByUser ($idUser)
```

## Todo

- token type
- config to allow only one token by user and type
- command to clear token table
- command to purge expired token