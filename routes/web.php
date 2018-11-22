<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
    //return view('index');
});
    
    
    
            //routes code promotional
            $router->get('code/{id}',['uses' => 'CodeController@getCode']);
            $router->get('code',['uses' => 'CodeController@getallCode']);
            $router->post('new-code', ['uses' => 'CodeController@createNewCode']);
            $router->get('code-actives',['uses' => 'CodeController@getCodesActive']);
            $router->post('deactive-codes',['uses' => 'CodeController@deactiveCodes']);
            
            //routes users
            
            $router->post('create-user',['uses' => 'UsersController@createNewUser']);
            $router->get('get-origin/{id}',['uses' => 'UsersController@getOrigin']);
            $router->post('new-origin',['uses' => 'UsersController@updateOrigin']);
           
           //routes events
           $router->post('config-radius',['uses' => 'EventsController@updateRadius']);
           $router->post('validate-code',['uses' => 'EventsController@validateCodeEvent']);
           $router->post('new-event',['uses' => 'EventsController@newEvent']);
           //$router->post('validate-code-event',['uses' => 'EventsController@validateCode']);
           
            
           //routes controller views
           $router->get('home', ['uses' => 'HomeController@inicio']);
           $router->post('principal',['uses' => 'HomeController@recibir']);
           
   
 
           
           
           