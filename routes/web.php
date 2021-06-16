<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
//REST best practices
//Code style ---- standard PSR-2
//Deploy ----

$router->get('/', function () use ($router) {
    return view('home');
});

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->group(['prefix' => 'system'], function () use ($router) {
        $router->get('status', 'SystemController@getCurrentStatus');

        $router->post('boot', 'SystemController@toogleMachine');
    });

    $router->group(['prefix' => 'queue'], function () use ($router) {

        $router->get('/', 'QueueController@index');

        $router->post('/', 'QueueController@store');

        $router->delete('{id}', 'QueueController@destroy');
    });
});
