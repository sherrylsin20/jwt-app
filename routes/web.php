<?php

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group([
    'prefix' => 'auth',
], function() use ($router) {
    $router->post('/register', 'UserController@register');
    $router->post('/login', 'UserController@login');

    $router->get('/logout', 'UserController@logout');
    $router->get('/user', 'UserController@index');
    $router->get('/me', 'UserController@me');
});