PHP Linkshare Client
==========================

[![Build Status](https://travis-ci.org/NMRKT/linkshare.svg)](https://travis-ci.org/NMRKT/linkshare)

A Guzzle client for consuming the Linkshare API

## How it works

This library uses the OAuth Plugin: https://github.com/NMRKT/guzzle5-oauth2-subscriber to subscribe to Guzzle Client calls and ensures that your client requests have the correct "Authorization" headers.

Currently there is only a client built for the [*events* API](https://developers.rakutenmarketing.com/subscribe/apis/info?name=Events&version=1.0&provider=LinkShare). Open to pull requests if anyone wants to tackle the Advanced Reports, Coupon, Product Search, etc. endpoints.

**VERY IMPORTANT TO SET 'grant_type' => 'password' IN YOUR CONFIG - Otherwise the OAuth Plugin will set it as 'client_credentials' and Linkshare won't know what that means.**

## Usage
```
<?php

use Nmrkt\Linkshare\Client\Events;

$config = [
    'grant_type' => 'password',
    'username' => 'your_linkshare_username',
    'password' => 'your_linkshare_password',
    'client_id' => 'your client id',
    'client_secret' => 'your client secret',
    'scope' => 'your scope(s)', // optional
];
//initialize the client with your API config
$client =  new Events($config);
//create the oauth subscirber
$subscriber = $client->getOauth2Subscriber();
//attach the oauth subscriber to the client
$client->attachOauth2Subscriber($subscriber);

// Now you can set params using the convenience methods following the 
$client->setProcessDateStart('2014-05-30 12:00:00');
$client->setLimit(1000);
//execute the query to the API
$transactions = $client->getTransactions();

```

###Wait, why do I have to attach the subsciber myself...?
This was implimented so that the library could be isolated and tested with mocks.
