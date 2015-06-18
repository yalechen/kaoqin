<?php
Route::get('/', function ()
{
    return view('welcome');
});

Route::get('register', [
    'as' => 'GetRegister',
    'uses' => 'Auth\AuthController@getRegister'
]);

Route::post('register', [
    'as' => 'PostRegister',
    'uses' => 'Auth\AuthController@postRegister'
]);

Route::get('login', [
    'as' => 'GetLogin',
    'uses' => 'Auth\AuthController@getLogin'
]);

Route::post('login', [
    'as' => 'PostLogin',
    'uses' => 'Auth\AuthController@postLogin'
]);

Route::get('Logout', [
    'as' => 'Logout',
    'uses' => 'Auth\AuthController@getLogout'
]);