Laravel token
=============

[![Build Status](https://travis-ci.org/lahaxearnaud/laravel-token.svg?branch=develop)](https://travis-ci.org/lahaxearnaud/laravel-token)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/2f9abd1c-42a6-4a80-88f4-e1687b1d361a/mini.png)](https://insight.sensiolabs.com/projects/2f9abd1c-42a6-4a80-88f4-e1687b1d361a)
[![CodeClimat](https://d3s6mut3hikguw.cloudfront.net/github/lahaxearnaud/laravel-token/badges/gpa.svg)](https://codeclimate.com/github/lahaxearnaud/laravel-token)
[![Test Coverage](https://codeclimate.com/github/lahaxearnaud/laravel-token/badges/coverage.svg)](https://codeclimate.com/github/lahaxearnaud/laravel-token)
[![License](https://poser.pugx.org/leaphly/cart-bundle/license.svg)](https://github.com/lahaxearnaud/laravel-token)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lahaxearnaud/laravel-token/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lahaxearnaud/laravel-token/?branch=master)

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
    + [Exceptions](#exceptions)
    + [Events](#events)
+ [Commands](#commands)
    + [Delete expired tokens](#delete-expired-tokens)
    + [Truncate the table](#truncate-the-table)
+ [API](#api)
    + [Security](#security)
    + [Creation](#creation)
    + [Deletion](#deletion)
    + [Validation](#validation)
    + [Find](#find)

## Installation


```json
{
    "require": {
        "lahaxearnaud/laravel-token": "~0.5"
    }
}
```

### Database

```bash
    $ php artisan migrate --package="lahaxearnaud/laravel-token"
```

### Provider

```php
	'providers' => array(
        // ...
		'Lahaxearnaud\LaravelToken\LaravelTokenServiceProvider',
	),
```

### Facade

```php
	'aliases' => array(
        // ...
		'Token' => 'Lahaxearnaud\LaravelToken\LaravelTokenFacade',
	),
```

## Usage

### Create token

```php
    $token = Token::create($userID, $allowLogin);
```

If ``$allowLogin`` is set to true the token can be use to authentification via route filter.


### Crypt token

```php
    $token = Token::create($userID, $allowLogin);
    $cryptToken = Token::cryptToken($token->token);
```

If ``$allowLogin`` is set to true the token can be use to authentification via route filter.


### Validate token

If you crypt your token

```php
    $tokenStr = Token::getTokenValueFromRequest();

    $cryptToken = Token::isValidCryptToken($token->token, $userId);
```

If you don't crypt your token:

```php
    $tokenStr = Token::getTokenValueFromRequest();

    $cryptToken = Token::isValidToken($token->token, $userId);
```

If you use those functions the token is not burn. It can be use many times.

For one shot usage token:

```php
    $tokenStr = Token::getTokenValueFromRequest();

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
    } catch (TokenNotFoundException $e) {
        $tokenValid = false;
    }

    if($tokenValid) {
        // manage errors
    }
```

### Route filter

Simple token protection:

```php
    Route::get('/token-protected', array('before' => 'token', function () {
        echo "I am token protected";
    }));
```

Authentification by token:

The token used for an authentification must be a login token, pleaserefer to the token creation section

```php
    Route::get('/login-by-token', array('before' => 'token.auth', function () {
        echo Auth::user()->username;
    }));
```

In order to use the authentification by token your class User need to implements ``Lahaxearnaud\LaravelToken\Models\UserTokenInterface``

```php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Lahaxearnaud\LaravelToken\Models\UserTokenInterface;

class User extends Eloquent implements UserInterface, RemindableInterface, UserTokenInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

    public function loggableByToken()
    {
        return true;
    }
}
```
The method ``loggableByToken`` is called when a user try to authentificate with a token.


If an error occur on token validation a TokenExeption is throw, please go to [Exceptions](#exceptions) section. 


By default you can send your token in parameter or header. The default name of the field is ``token`` but you 
can change it by publishing and change the configuration:

```bash
    $ php artisan config:publish lahaxearnaud/laravel-token
```

Then change the tokenFieldName ``config/packages/lahaxearnaud/laravel-token/config.php``.

You can get the token instance via:
```php
    Token::getCurrentToken();
```

### Exceptions

If you use route filter you need to handle some Exceptions. Add the following error handler in you ``filter.php`` to catch them.
This is basic example, change the behavior to fit your needs (redirect, log...).

```php
    App::error(function(\Lahaxearnaud\LaravelToken\exeptions\TokenException $exception)
    {
        if($exception instanceof \Lahaxearnaud\LaravelToken\exeptions\TokenNotFoundException) {
            return \Response::make('Unauthorized (Not found)', 401);
        }

        if($exception instanceof \Lahaxearnaud\LaravelToken\exeptions\TokenNotValidException) {
            return \Response::make('Unauthorized (Not valid token)', 401);
        }

        if($exception instanceof \Lahaxearnaud\LaravelToken\exeptions\UserNotLoggableByTokenException) {
            return \Response::make('Unauthorized (Not loggable by token)', 401);
        }

        if($exception instanceof \Lahaxearnaud\LaravelToken\exeptions\NotLoginTokenException) {
            return \Response::make('Unauthorized (Not login token)', 401);
        }
    });
```

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

## Commands
    A new artisan command is added to your project in order to help you to clean your token table
    
    ### Delete expired tokens
        Without any option the command delete all expired tokens.
        ```bash
            $ php artisan token:clean
        ```
    ### Truncate the table
        If you specified ``--all`` all token will be deleted
        ```bash
            $ php artisan token:clean -all
        ```
## API

### Security

Crypt a string token in order to get a public token

```php
    Token::cryptToken ($uncrypt)
```

Uncrypt a public token in order to get the private token

```php
    Token::uncryptToken ($crypt)
```

### Creation

Create a Token instance (directly saved in database)

```php
    Token::create ($userId, $allowLogin = false, $lifetime = 3600, $length = 100)
```

If ``$allowLogin`` is set to true the token can be use to authentification via route filter.

### Deletion

Delete the token

```php
    Token::burn (Token $token)
```

### Validation

Fetch the token, check id the token has the good user ID and if it is not expired

```php
    Token::isValidToken ($token, $userId)
```

Same as isValidToken but uncrypt the token before trying to find him

```php
    Token::isValidCryptToken ($token, $userId)
```

Only validate if the token is expired

```php
    Token::isValid (Token $token)
```

### Find

Find the token by ID

```php
    Token::find ($id)
```

Find the token by token string

```php
    Token::findByToken ($token, $userId)
```

Find all token for an user

```php
    Token::findByUser ($idUser)
```

## Todo
- config to allow only one token by user and type
