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
//@TODO usar controladores y modelos
//REST best practices
//Code style ---- standard PSR-2
//Deploy ----

$router->get('/', function () use ($router) {
    return view('home');
});

$router->group(['prefix' => 'api'], function () use ($router) {

    $router->get('status', function () {
        if( count(app('db')->select("SELECT 1 FROM system")) > 0 ) {
            $buttonText  = 'Stop';
            $message     = "running";
        } else {
            $buttonText  = 'Start';
            $message     = "stopped";
        }
        return response()->json([
            "success" => true,
            "message" => $message,
            "data"    => [
                'buttonText' => $buttonText
            ]
        ]);
    });

    $router->post('/boot', function () {
        if( count(app('db')->select("SELECT 1 FROM system")) === 0 ) {
            app('db')->table('system')->insert(["status"=>"running"]);
            $buttonText  = 'Stop';
            $message     = "running";
        } else {
            app('db')->table('system')->truncate();
            $buttonText  = 'Start';
            $message     = "stoped";
        }

        return response()->json([
            "success" => true,
            "message" => $message,
            "data"    => [
                "buttonText" => $buttonText 
            ],
        ]);
    });

    $router->group(['prefix' => 'queue'], function () use ($router) {

        $router->get('/', 'QueueController@index');

        $router->post('/', 'QueueController@store');


        $router->delete('{id}', 'QueueController@destroy');
    });
});
