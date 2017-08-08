<?php

$app->get('/', function () use ($app) {
    return $app->version() . " - Sua conta foi confirmada !!!";
});

$app->group(['prefix' => 'api'], function () use ($app) {
    $app->get('/user', 'RegisterController@index');
    $app->post('/user', 'RegisterController@register');
    $app->get('/verification-account/{token}', [
        'as' => 'verification_account', 'uses' => 'RegisterController@accountVerification'
    ]);
    $app->post('/login', 'AuthController@login');
    $app->post('/refresh-token', ['middleware' => 'auth', 'AuthController@refreshToken']);

    $app->group(['middleware' => ['auth', 'is-verified', 'token-expired']], function () use ($app) {
        $app->get('/clients', 'clientController@findAllClients');
        $app->get('/user-auth', 'UserController@getAuthenticatedUser');
    });
});
