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
    + [Facase](#facase)
+ [Usage](#usage)
    + [Create token](#create-token)
    + [Crypt token](#crypt-token)
    + [Validate token](#validate-token)
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

### Provider

```
	'providers' => array(
        // ...
		'Lahaxearnaud\LaravelToken\LaravelTokenServiceProvider',
	),
```

### Facase

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

## API

### Security

Crypt a string token

```
    public function cryptToken ($uncrypt)
```

Uncrypt a string token

```
    public function uncryptToken ($crypt)
```

### Creation

Create a Token instance

```
    public function create ($userId, $lifetime = 3600, $length = 100)
```

Insert the token in database

```
    public function persist (Token $token)
```

### Deletion

Delete the token

```
    public function burn (Token $token)
```

### Validation

Fetch the token, check id the token has the good user ID and if it is not expired

```
    public function isValidToken ($token, $userId)
```

Same as isValidToken but uncrypt the token before trying to find him

```
    public function isValidCryptToken ($token, $userId)
```

Only validate if the token is expired

```
    public function isValid (Token $token)
```

### Find

Find the token by ID

```
    public function find ($id)
```

Find the token by token string

```
    public function findByToken ($token, $userId)
```

Find all token for an user

```
    public function findByUser ($idUser)
```

