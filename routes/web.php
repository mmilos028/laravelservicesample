<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//default route

Route::get('/', 'Rest\Json\IndexController@methodNotAllowed')->middleware('cors');

Route::post('/', 'Rest\Json\IndexController@index')->middleware('cors');

Route::match(array('GET', 'POST'), '/document', 'DocumentController@index');

////// REST ROUTES //////

Route::group(
    [
        'prefix' => 'rest',
        'middleware' => ['cors']
    ],
    function()
    {
        Route::match(array('GET'), '/', 'Rest\Json\IndexController@methodNotAllowed');
        Route::match(array('POST'), '/', 'Rest\Json\IndexController@index');

        Route::group(
            [
                'prefix' => 'json',
                'middleware' => ['cors']
            ],
            function()
            {
                Route::match(array('GET'), '/', 'Rest\Json\IndexController@methodNotAllowed');
                Route::match(array('POST'), '/', 'Rest\Json\IndexController@index');
            }
        );

        Route::group(
            [
                'prefix' => 'xml',
                'middleware' => ['cors']
            ],
            function()
            {
                Route::match(array('GET'), '/', 'Rest\Json\IndexController@methodNotAllowed');
                Route::match(array('POST'), '/', 'Rest\Json\IndexController@index');
            }
        );
    }
);

//Database listener to firebug logs

if (env('DB_WRITE_TO_FIREBUG', false) == true) {
    DB::listen(function ($query) {
        // Get an instance of Monolog
        $monolog = new \Monolog\Logger('firephp');
        // Now add some handlers
        $monolog->pushHandler(new \Monolog\Handler\FirePHPHandler());
        // Start logging
        $time = date("d-m-Y H:i:s u");
        $message = "SQL {$time}";
        $monolog->info($message, array('query' => $query));
    });
}
