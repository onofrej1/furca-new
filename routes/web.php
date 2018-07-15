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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'cors'], function() {
  Route::resources([
      'article' => 'ArticleController',
      'page' => 'PageController',
      'menu' => 'MenuController',
      'menuItem' => 'MenuItemController',
      'tag' => 'TagController',
      'user' => 'UserController',
      'role' => 'RoleController',
      'event' => 'EventController',
      'run' => 'RunController',
      'result' => 'ResultController',
      'hamburg' => 'HamburgController',
      'permission' => 'PermissionController',
      'news' => 'NewsController',
      'guestbook' => 'GuestbookController'
  ]);

  Route::get('/verify-captcha', 'HomeController@verifyCaptcha')->name('verifyCaptcha');
  Route::match(['get', 'post'], '/files', 'FileController@getFiles')->name('verifyCaptcha');

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/hamburg/results/{event}', 'HamburgController@getResults')->name('csv');
