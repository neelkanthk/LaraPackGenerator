<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */

Route::get('/test', ['uses' => 'SiteController@test']);
Route::get('/', [
    'as' => 'rt_home', 'uses' => 'SiteController@home'
]);

Route::post('/generate', [
    'as' => 'rt_generate', 'uses' => 'GeneratorController@generatepackage'
]);
