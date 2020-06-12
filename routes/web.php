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

Route::get('/register',
[
    'uses' => 'RouteController@toSignlog',
    'as' => 'login'
]);

Route::get('/',
[
    'uses' => 'RouteController@toProducts',
    'as' => 'products'
]);

Route::post('/signup',
[
    'uses' => 'UserController@SignUp',
    'as' => 'signup'
]);

Route::post('/login',
[
    'uses' => 'UserController@login',
    'as' => 'login1'
]);

Route::get('/logout',
[
    'uses' => 'UserController@logout',
    'as' => 'logout'
]);

Route::get('/profile',
[
    'uses' => 'RouteController@getProfile',
    'as' => 'profile',
    'middleware' => 'auth'
]);

Route::get('/user/{user_id}',
[
    'uses' => 'RouteController@getUser',
    'as' => 'getUser'
]);

Route::post('/dp',
[
    'uses' => 'UserController@changeDp',
    'as' => 'dp'
]);

Route::post('/addProduct',
[
    'uses' => 'ProductController@addProduct',
    'as' => 'addProduct'
]);

Route::get('/details/{product_id}',
[
    'uses' => 'ProductController@getDetails',
    'as' => 'getDetail'
]);

Route::post('/addreview',
[
    'uses' => 'CommentsController@addreview',
    'as' => 'addreview'
]);
